import 'package:flutter/material.dart';
import 'package:battery_plus/battery_plus.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants.dart';
import '../services/background_service.dart';
import 'login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final BackgroundGatewayService _service = BackgroundGatewayService();
  final Battery _battery = Battery();

  bool _isRunning = false;
  int _batteryLevel = 0;
  int _messagesSent = 0;
  int _messagesFailed = 0;
  String _status = 'To\'xtagan';
  String? _apiKey;

  @override
  void initState() {
    super.initState();
    _loadState();
  }

  Future<void> _loadState() async {
    final prefs = await SharedPreferences.getInstance();
    final isRunning = prefs.getBool(AppConstants.keyIsRunning) ?? false;
    final apiKey = prefs.getString(AppConstants.keyApiKey);
    final batteryLevel = await _battery.batteryLevel;

    setState(() {
      _isRunning = isRunning;
      _apiKey = apiKey;
      _batteryLevel = batteryLevel;
      _status = isRunning ? 'Ishlayapti' : 'To\'xtagan';
    });

    if (isRunning) {
      await _service.start();
    }
  }

  Future<void> _toggleService() async {
    if (_isRunning) {
      await _service.stop();
      setState(() {
        _isRunning = false;
        _status = 'To\'xtagan';
      });
    } else {
      await _service.start();
      setState(() {
        _isRunning = true;
        _status = 'Ishlayapti';
      });
    }
  }

  Future<void> _logout() async {
    await _service.stop();
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
    if (mounted) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => const LoginScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF1A1A2E),
      appBar: AppBar(
        backgroundColor: const Color(0xFF16213E),
        title: const Text('SMS Gateway', style: TextStyle(color: Colors.white)),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.white70),
            onPressed: _logout,
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Status Card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: _isRunning
                      ? [const Color(0xFF0F3460), const Color(0xFF1A5276)]
                      : [const Color(0xFF2C2C34), const Color(0xFF3D3D47)],
                ),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                children: [
                  Icon(
                    _isRunning ? Icons.wifi_tethering : Icons.wifi_tethering_off,
                    color: _isRunning ? Colors.greenAccent : Colors.grey,
                    size: 60,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    _status,
                    style: TextStyle(
                      color: _isRunning ? Colors.greenAccent : Colors.grey,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    _isRunning
                        ? 'SMS xabarlari qabul qilinmoqda va yuborilmoqda'
                        : 'Xizmatni ishga tushiring',
                    style: TextStyle(color: Colors.grey[400], fontSize: 13),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 24),

                  // Toggle button
                  SizedBox(
                    width: 200,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _toggleService,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _isRunning ? Colors.redAccent : Colors.greenAccent,
                        foregroundColor: _isRunning ? Colors.white : Colors.black,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(25),
                        ),
                      ),
                      child: Text(
                        _isRunning ? 'To\'xtatish' : 'Ishga tushirish',
                        style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Stats Grid
            Row(
              children: [
                Expanded(
                  child: _statCard(
                    icon: Icons.send,
                    label: 'Yuborilgan',
                    value: '$_messagesSent',
                    color: Colors.blueAccent,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _statCard(
                    icon: Icons.error_outline,
                    label: 'Xato',
                    value: '$_messagesFailed',
                    color: Colors.redAccent,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _statCard(
                    icon: Icons.battery_charging_full,
                    label: 'Batareya',
                    value: '$_batteryLevel%',
                    color: _batteryLevel > 20 ? Colors.greenAccent : Colors.orangeAccent,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _statCard(
                    icon: Icons.signal_cellular_alt,
                    label: 'Signal',
                    value: 'Yaxshi',
                    color: Colors.purpleAccent,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),

            // API Key display
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: const Color(0xFF16213E),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('API Key', style: TextStyle(color: Colors.grey[400], fontSize: 12)),
                  const SizedBox(height: 4),
                  Text(
                    _apiKey ?? '...',
                    style: const TextStyle(color: Colors.white70, fontSize: 12, fontFamily: 'monospace'),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _statCard({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFF16213E),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 24),
          const SizedBox(height: 12),
          Text(
            value,
            style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 4),
          Text(label, style: TextStyle(color: Colors.grey[400], fontSize: 12)),
        ],
      ),
    );
  }
}
