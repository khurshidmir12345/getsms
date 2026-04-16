import 'dart:async';
import 'package:battery_plus/battery_plus.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/api_client.dart';
import '../core/constants.dart';
import '../models/message.dart';
import 'sms_service.dart';

class BackgroundGatewayService {
  final ApiClient _apiClient = ApiClient();
  final SmsService _smsService = SmsService();
  final Battery _battery = Battery();

  Timer? _pollingTimer;
  Timer? _heartbeatTimer;
  Timer? _batteryTimer;
  bool _isRunning = false;

  bool get isRunning => _isRunning;

  Future<void> start() async {
    if (_isRunning) return;

    await _apiClient.init();
    _isRunning = true;

    // Save state
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool(AppConstants.keyIsRunning, true);

    // Start foreground service
    await _smsService.startForegroundService();

    // Listen for SMS status updates
    _smsService.statusStream.listen(_handleSmsStatus);

    // Start polling for pending messages
    _pollingTimer = Timer.periodic(
      const Duration(seconds: AppConstants.pollingIntervalSeconds),
      (_) => _pollPendingMessages(),
    );

    // Start heartbeat
    _heartbeatTimer = Timer.periodic(
      const Duration(seconds: AppConstants.heartbeatIntervalSeconds),
      (_) => _sendHeartbeat(),
    );

    // Start battery reporting
    _batteryTimer = Timer.periodic(
      const Duration(seconds: AppConstants.batteryReportIntervalSeconds),
      (_) => _reportBattery(),
    );

    // Initial poll
    await _pollPendingMessages();
    await _sendHeartbeat();
  }

  Future<void> stop() async {
    _isRunning = false;
    _pollingTimer?.cancel();
    _heartbeatTimer?.cancel();
    _batteryTimer?.cancel();

    await _smsService.stopForegroundService();

    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool(AppConstants.keyIsRunning, false);
  }

  Future<void> _pollPendingMessages() async {
    if (!_isRunning) return;

    try {
      final messages = await _apiClient.getPendingMessages(limit: 5);
      for (final msgData in messages) {
        final msg = SmsMessage.fromJson(msgData);
        await _processSms(msg);
        // Small delay between messages to avoid carrier throttling
        await Future.delayed(const Duration(seconds: 3));
      }
    } catch (e) {
      print('Polling error: $e');
    }
  }

  Future<void> _processSms(SmsMessage message) async {
    try {
      // Notify backend that we're sending
      await _apiClient.updateMessageStatus(message.id, 'sending');

      // Send via native Android
      final success = await _smsService.sendSms(
        phone: message.phoneTo,
        body: message.body,
        messageId: message.id,
      );

      if (!success) {
        await _apiClient.updateMessageStatus(
          message.id, 'failed',
          errorMessage: 'Failed to initiate SMS send',
        );
      }
      // Actual status (sent/delivered/failed) will come through _handleSmsStatus
    } catch (e) {
      await _apiClient.updateMessageStatus(
        message.id, 'failed',
        errorMessage: e.toString(),
      );
    }
  }

  void _handleSmsStatus(Map<String, dynamic> event) async {
    final messageId = event['messageId'] as int;
    final status = event['status'] as String;
    final errorMessage = event['errorMessage'] as String?;

    try {
      await _apiClient.updateMessageStatus(messageId, status, errorMessage: errorMessage);
    } catch (e) {
      print('Status update error: $e');
    }
  }

  Future<void> _sendHeartbeat() async {
    if (!_isRunning) return;
    try {
      final batteryLevel = await _battery.batteryLevel;
      await _apiClient.sendHeartbeat(
        batteryLevel: batteryLevel,
        signalStrength: null,
      );
    } catch (e) {
      print('Heartbeat error: $e');
    }
  }

  Future<void> _reportBattery() async {
    if (!_isRunning) return;
    try {
      final batteryLevel = await _battery.batteryLevel;
      await _apiClient.reportBattery(batteryLevel, 0);
    } catch (e) {
      print('Battery report error: $e');
    }
  }

  /// Register device with backend
  Future<Map<String, dynamic>?> registerDevice(String apiKey, String baseUrl) async {
    _apiClient.setBaseUrl(baseUrl);

    try {
      final deviceInfo = await _smsService.getDeviceInfo();
      final simInfo = await _smsService.getSimInfo();

      final result = await _apiClient.registerDevice(
        apiKey: apiKey,
        deviceId: deviceInfo['deviceId'] ?? 'unknown',
        name: deviceInfo['model'] ?? 'Android Device',
        phoneNumber: simInfo.isNotEmpty ? simInfo[0]['number'] : null,
        operator_: simInfo.isNotEmpty ? simInfo[0]['operator'] : null,
        simSlots: simInfo,
        model: deviceInfo['model'],
        androidVersion: deviceInfo['androidVersion'],
      );

      if (result['success'] == true) {
        final token = result['device_token'] as String;
        _apiClient.setDeviceToken(token);

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(AppConstants.keyDeviceToken, token);
        await prefs.setString(AppConstants.keyApiKey, apiKey);
        await prefs.setString(AppConstants.keyBaseUrl, baseUrl);

        return result;
      }
      return result;
    } catch (e) {
      return {'success': false, 'error': e.toString()};
    }
  }
}
