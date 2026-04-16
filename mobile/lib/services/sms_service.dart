import 'dart:async';
import 'package:flutter/services.dart';

class SmsService {
  static const _channel = MethodChannel('uz.smsgateway/sms');
  static const _eventChannel = EventChannel('uz.smsgateway/sms_status');

  Stream<Map<String, dynamic>>? _statusStream;

  /// Send an SMS via native Android API
  Future<bool> sendSms({
    required String phone,
    required String body,
    required int messageId,
    int simSlot = 0,
  }) async {
    try {
      final result = await _channel.invokeMethod('sendSms', {
        'phone': phone,
        'body': body,
        'messageId': messageId,
        'simSlot': simSlot,
      });
      return result == true;
    } on PlatformException catch (e) {
      print('SMS send error: ${e.message}');
      return false;
    }
  }

  /// Get device information
  Future<Map<String, dynamic>> getDeviceInfo() async {
    try {
      final result = await _channel.invokeMethod('getDeviceInfo');
      return Map<String, dynamic>.from(result);
    } catch (e) {
      return {};
    }
  }

  /// Get SIM card information
  Future<List<Map<String, dynamic>>> getSimInfo() async {
    try {
      final result = await _channel.invokeMethod('getSimInfo');
      return List<Map<String, dynamic>>.from(
        (result as List).map((e) => Map<String, dynamic>.from(e)),
      );
    } catch (e) {
      return [];
    }
  }

  /// Start foreground service
  Future<void> startForegroundService() async {
    await _channel.invokeMethod('startForegroundService');
  }

  /// Stop foreground service
  Future<void> stopForegroundService() async {
    await _channel.invokeMethod('stopForegroundService');
  }

  /// Listen to SMS delivery status events
  Stream<Map<String, dynamic>> get statusStream {
    _statusStream ??= _eventChannel
        .receiveBroadcastStream()
        .map((event) => Map<String, dynamic>.from(event));
    return _statusStream!;
  }
}
