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

class _HomeScreenState extends State<HomeScreen>
    with SingleTickerProviderStateMixin {
  final BackgroundGatewayService _service = BackgroundGatewayService();
  final Battery _battery = Battery();

  bool _isRunning = false;
  int _batteryLevel = 0;
  int _messagesSent = 0;
  int _messagesFailed = 0;
  String? _apiKey;
  String? _baseUrl;
  String? _deviceToken;
  DateTime? _lastHeartbeat;

  // Pulse animation
  late AnimationController _pulseController;
  late Animation<double> _pulseAnimation;

  // Colors
  static const _bg = Color(0xFF0F172A);
  static const _surface = Color(0xFF1E293B);
  static const _indigo = Color(0xFF4F46E5);
  static const _indigoLight = Color(0xFF6366F1);
  static const _emerald = Color(0xFF10B981);
  static const _textMuted = Color(0xFF94A3B8);

  @override
  void initState() {
    super.initState();
    _pulseController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1400),
    )..repeat(reverse: true);
    _pulseAnimation = Tween<double>(begin: 0.85, end: 1.0).animate(
      CurvedAnimation(parent: _pulseController, curve: Curves.easeInOut),
    );
    _loadState();
  }

  Future<void> _loadState() async {
    final prefs = await SharedPreferences.getInstance();
    final isRunning = prefs.getBool(AppConstants.keyIsRunning) ?? false;
    final apiKey = prefs.getString(AppConstants.keyApiKey);
    final baseUrl = prefs.getString(AppConstants.keyBaseUrl);
    final deviceToken = prefs.getString(AppConstants.keyDeviceToken);
    final batteryLevel = await _battery.batteryLevel;

    setState(() {
      _isRunning = isRunning;
      _apiKey = apiKey;
      _baseUrl = baseUrl ?? AppConstants.defaultBaseUrl;
      _deviceToken = deviceToken;
      _batteryLevel = batteryLevel;
      _lastHeartbeat = DateTime.now();
    });

    if (isRunning) {
      await _service.start();
    }
  }

  Future<void> _toggleService() async {
    if (_isRunning) {
      await _service.stop();
      setState(() => _isRunning = false);
    } else {
      await _service.start();
      setState(() {
        _isRunning = true;
        _lastHeartbeat = DateTime.now();
      });
    }
  }

  Future<void> _refreshBattery() async {
    final level = await _battery.batteryLevel;
    setState(() {
      _batteryLevel = level;
      _lastHeartbeat = DateTime.now();
    });
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

  void _showSettingsSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: _surface,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) => Padding(
        padding: const EdgeInsets.fromLTRB(24, 20, 24, 40),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Handle bar
            Center(
              child: Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: const Color(0xFF334155),
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
            ),
            const SizedBox(height: 20),
            const Text(
              'Sozlamalar',
              style: TextStyle(
                color: Colors.white,
                fontSize: 18,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 20),

            // API Key
            _settingsRow(
              label: 'API Key',
              value: _apiKey ?? '—',
              icon: Icons.key_rounded,
              mono: true,
            ),
            const SizedBox(height: 12),

            // Base URL
            _settingsRow(
              label: 'Server URL',
              value: _baseUrl ?? AppConstants.defaultBaseUrl,
              icon: Icons.dns_rounded,
            ),
            const SizedBox(height: 24),

            // Divider
            const Divider(color: Color(0xFF334155), height: 1),
            const SizedBox(height: 20),

            // Logout
            SizedBox(
              width: double.infinity,
              height: 48,
              child: OutlinedButton.icon(
                onPressed: () {
                  Navigator.pop(context);
                  _logout();
                },
                icon: const Icon(Icons.logout_rounded,
                    color: Color(0xFFEF4444), size: 18),
                label: const Text(
                  'Chiqish',
                  style: TextStyle(
                    color: Color(0xFFEF4444),
                    fontWeight: FontWeight.w600,
                  ),
                ),
                style: OutlinedButton.styleFrom(
                  side: const BorderSide(
                      color: Color(0xFFEF4444), width: 1),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _settingsRow({
    required String label,
    required String value,
    required IconData icon,
    bool mono = false,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFF0F172A),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFF334155), width: 1),
      ),
      child: Row(
        children: [
          Icon(icon, color: _indigoLight, size: 18),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label,
                    style:
                        const TextStyle(color: _textMuted, fontSize: 11)),
                const SizedBox(height: 2),
                Text(
                  value,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 13,
                    fontFamily: mono ? 'monospace' : null,
                    fontWeight: FontWeight.w500,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatTime(DateTime? dt) {
    if (dt == null) return '—';
    final h = dt.hour.toString().padLeft(2, '0');
    final m = dt.minute.toString().padLeft(2, '0');
    final s = dt.second.toString().padLeft(2, '0');
    return '$h:$m:$s';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: _bg,
      appBar: AppBar(
        backgroundColor: _bg,
        elevation: 0,
        surfaceTintColor: Colors.transparent,
        title: Row(
          children: [
            Container(
              width: 32,
              height: 32,
              decoration: BoxDecoration(
                color: _indigo.withValues(alpha: 0.15),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(Icons.sms_rounded,
                  color: _indigoLight, size: 16),
            ),
            const SizedBox(width: 10),
            const Text(
              'SMS Gateway',
              style: TextStyle(
                color: Colors.white,
                fontSize: 18,
                fontWeight: FontWeight.w700,
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: _surface,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(
                    color: const Color(0xFF334155), width: 1),
              ),
              child: const Icon(Icons.settings_rounded,
                  color: _textMuted, size: 18),
            ),
            onPressed: _showSettingsSheet,
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refreshBattery,
        color: _indigoLight,
        backgroundColor: _surface,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.fromLTRB(20, 8, 20, 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Status section
              _buildStatusSection(),
              const SizedBox(height: 20),

              // Stats grid
              _buildStatsGrid(),
              const SizedBox(height: 20),

              // Connection info
              _buildConnectionCard(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusSection() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 36, horizontal: 24),
      decoration: BoxDecoration(
        color: _surface,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: _isRunning
              ? _emerald.withValues(alpha: 0.2)
              : const Color(0xFF334155),
          width: 1,
        ),
      ),
      child: Column(
        children: [
          // Pulsing ring indicator
          AnimatedSwitcher(
            duration: const Duration(milliseconds: 400),
            child: _isRunning
                ? AnimatedBuilder(
                    key: const ValueKey('running'),
                    animation: _pulseAnimation,
                    builder: (_, __) => Transform.scale(
                      scale: _pulseAnimation.value,
                      child: _statusRing(
                        color: _emerald,
                        label: 'Faol',
                        icon: Icons.wifi_tethering_rounded,
                      ),
                    ),
                  )
                : _statusRing(
                    key: const ValueKey('stopped'),
                    color: const Color(0xFF475569),
                    label: "To'xtagan",
                    icon: Icons.wifi_tethering_off_rounded,
                  ),
          ),
          const SizedBox(height: 28),

          // Toggle button
          AnimatedSwitcher(
            duration: const Duration(milliseconds: 300),
            child: SizedBox(
              key: ValueKey(_isRunning),
              width: double.infinity,
              height: 52,
              child: ElevatedButton.icon(
                onPressed: _toggleService,
                icon: Icon(
                  _isRunning
                      ? Icons.stop_rounded
                      : Icons.play_arrow_rounded,
                  size: 22,
                ),
                label: Text(
                  _isRunning ? "To'xtatish" : 'Ishga tushirish',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor:
                      _isRunning ? _emerald : _indigo,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _statusRing({
    Key? key,
    required Color color,
    required String label,
    required IconData icon,
  }) {
    return Column(
      key: key,
      children: [
        Container(
          width: 100,
          height: 100,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            border: Border.all(color: color, width: 3),
            color: color.withValues(alpha: 0.08),
          ),
          child: Center(
            child: Icon(icon, color: color, size: 44),
          ),
        ),
        const SizedBox(height: 14),
        Text(
          label,
          style: TextStyle(
            color: color,
            fontSize: 20,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.2,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          _isRunning
              ? 'SMS xabarlari qabul qilinmoqda'
              : 'Xizmatni ishga tushiring',
          style: const TextStyle(color: _textMuted, fontSize: 13),
        ),
      ],
    );
  }

  Widget _buildStatsGrid() {
    final batteryColor = _batteryLevel > 50
        ? _emerald
        : _batteryLevel > 20
            ? const Color(0xFFF59E0B)
            : const Color(0xFFEF4444);

    return Column(
      children: [
        Row(
          children: [
            Expanded(
              child: _statCard(
                icon: Icons.send_rounded,
                label: 'Yuborilgan',
                value: '$_messagesSent',
                color: _indigoLight,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _statCard(
                icon: Icons.error_outline_rounded,
                label: 'Xato',
                value: '$_messagesFailed',
                color: const Color(0xFFEF4444),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _statCard(
                icon: Icons.battery_charging_full_rounded,
                label: 'Batareya',
                value: '$_batteryLevel%',
                color: batteryColor,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _statCard(
                icon: Icons.signal_cellular_alt_rounded,
                label: 'Signal',
                value: 'Yaxshi',
                color: _emerald,
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _statCard({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: _surface,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
            color: const Color(0xFF334155), width: 1),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 36,
            height: 36,
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, color: color, size: 18),
          ),
          const SizedBox(height: 14),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 22,
              fontWeight: FontWeight.w800,
              letterSpacing: -0.5,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            style: const TextStyle(color: _textMuted, fontSize: 12),
          ),
        ],
      ),
    );
  }

  Widget _buildConnectionCard() {
    final deviceName = _deviceToken != null
        ? 'Device ${_deviceToken!.substring(0, 8)}...'
        : 'Noma\'lum qurilma';

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: _surface,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
            color: const Color(0xFF334155), width: 1),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.info_outline_rounded,
                  color: _indigoLight, size: 18),
              const SizedBox(width: 8),
              const Text(
                'Ulanish ma\'lumotlari',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 15,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          const Divider(color: Color(0xFF334155), height: 1),
          const SizedBox(height: 14),
          _infoRow(
            icon: Icons.dns_rounded,
            label: 'Server',
            value: _baseUrl ?? AppConstants.defaultBaseUrl,
          ),
          const SizedBox(height: 10),
          _infoRow(
            icon: Icons.phone_android_rounded,
            label: 'Qurilma',
            value: deviceName,
          ),
          const SizedBox(height: 10),
          _infoRow(
            icon: Icons.access_time_rounded,
            label: 'So\'nggi signal',
            value: _formatTime(_lastHeartbeat),
          ),
        ],
      ),
    );
  }

  Widget _infoRow({
    required IconData icon,
    required String label,
    required String value,
  }) {
    return Row(
      children: [
        Icon(icon, color: _textMuted, size: 16),
        const SizedBox(width: 10),
        Text(
          '$label: ',
          style: const TextStyle(color: _textMuted, fontSize: 13),
        ),
        Expanded(
          child: Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 13,
              fontWeight: FontWeight.w500,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            textAlign: TextAlign.right,
          ),
        ),
      ],
    );
  }

  @override
  void dispose() {
    _pulseController.dispose();
    super.dispose();
  }
}
