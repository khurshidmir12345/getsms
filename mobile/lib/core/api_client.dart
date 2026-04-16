import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'constants.dart';

class ApiClient {
  late Dio _dio;
  String? _deviceToken;
  String? _baseUrl;

  ApiClient() {
    _dio = Dio(BaseOptions(
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
    ));
  }

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _deviceToken = prefs.getString(AppConstants.keyDeviceToken);
    _baseUrl = prefs.getString(AppConstants.keyBaseUrl) ?? AppConstants.defaultBaseUrl;
    _dio.options.baseUrl = _baseUrl!;
    if (_deviceToken != null) {
      _dio.options.headers['Authorization'] = 'Bearer $_deviceToken';
    }
  }

  void setDeviceToken(String token) {
    _deviceToken = token;
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }

  void setBaseUrl(String url) {
    _baseUrl = url;
    _dio.options.baseUrl = url;
  }

  // Device registration
  Future<Map<String, dynamic>> registerDevice({
    required String apiKey,
    required String deviceId,
    required String name,
    String? phoneNumber,
    String? operator_,
    List<Map<String, dynamic>>? simSlots,
    String? model,
    String? androidVersion,
  }) async {
    final response = await _dio.post('/device/register', data: {
      'api_key': apiKey,
      'device_id': deviceId,
      'name': name,
      'phone_number': phoneNumber,
      'operator': operator_,
      'sim_slots': simSlots,
      'model': model,
      'android_version': androidVersion,
    });
    return response.data;
  }

  // Heartbeat
  Future<void> sendHeartbeat({int? batteryLevel, int? signalStrength}) async {
    await _dio.post('/device/heartbeat', data: {
      'battery_level': batteryLevel,
      'signal_strength': signalStrength,
    });
  }

  // Get pending messages
  Future<List<Map<String, dynamic>>> getPendingMessages({int limit = 10}) async {
    final response = await _dio.get('/messages/pending', queryParameters: {'limit': limit});
    final data = response.data;
    if (data['success'] == true) {
      return List<Map<String, dynamic>>.from(data['messages']);
    }
    return [];
  }

  // Update message status
  Future<void> updateMessageStatus(int messageId, String status, {String? errorMessage}) async {
    await _dio.post('/messages/$messageId/status', data: {
      'status': status,
      'error_message': errorMessage,
    });
  }

  // Report incoming message
  Future<void> reportIncomingMessage(String phoneFrom, String body) async {
    await _dio.post('/messages/incoming', data: {
      'phone_from': phoneFrom,
      'body': body,
    });
  }

  // Bulk status update
  Future<void> bulkUpdateStatus(List<Map<String, dynamic>> statuses) async {
    await _dio.post('/messages/bulk-status', data: {
      'statuses': statuses,
    });
  }

  // Battery report
  Future<void> reportBattery(int batteryLevel, int signalStrength) async {
    await _dio.post('/device/battery', data: {
      'battery_level': batteryLevel,
      'signal_strength': signalStrength,
    });
  }
}
