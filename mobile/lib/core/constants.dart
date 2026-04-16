class AppConstants {
  static const String appName = 'SMS Gateway';
  static const String defaultBaseUrl = 'http://10.0.2.2:8000/api';

  // Polling intervals
  static const int heartbeatIntervalSeconds = 30;
  static const int pollingIntervalSeconds = 10;
  static const int batteryReportIntervalSeconds = 60;

  // SharedPreferences keys
  static const String keyApiKey = 'api_key';
  static const String keyDeviceToken = 'device_token';
  static const String keyBaseUrl = 'base_url';
  static const String keyIsRunning = 'is_running';
}
