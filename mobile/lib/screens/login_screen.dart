import 'package:flutter/material.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants.dart';
import '../services/background_service.dart';
import 'home_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _apiKeyController = TextEditingController();
  final _baseUrlController =
      TextEditingController(text: AppConstants.defaultBaseUrl);
  final BackgroundGatewayService _service = BackgroundGatewayService();
  bool _isLoading = false;
  String? _error;
  bool _logoVisible = false;

  // Colors
  static const _bg = Color(0xFF0F172A);
  static const _surface = Color(0xFF1E293B);
  static const _indigo = Color(0xFF4F46E5);
  static const _indigoLight = Color(0xFF6366F1);
  static const _textMuted = Color(0xFF94A3B8);

  @override
  void initState() {
    super.initState();
    _checkExistingConfig();
    _requestPermissions();
    // Trigger logo animation after first frame
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) setState(() => _logoVisible = true);
    });
  }

  Future<void> _checkExistingConfig() async {
    final prefs = await SharedPreferences.getInstance();
    final apiKey = prefs.getString(AppConstants.keyApiKey);
    final baseUrl = prefs.getString(AppConstants.keyBaseUrl);
    final token = prefs.getString(AppConstants.keyDeviceToken);

    if (apiKey != null && token != null) {
      _apiKeyController.text = apiKey;
      if (baseUrl != null) _baseUrlController.text = baseUrl;

      if (mounted) {
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(builder: (_) => const HomeScreen()),
        );
      }
    }
  }

  Future<void> _requestPermissions() async {
    await [
      Permission.sms,
      Permission.phone,
      Permission.notification,
    ].request();

    if (await Permission.ignoreBatteryOptimizations.isDenied) {
      await Permission.ignoreBatteryOptimizations.request();
    }
  }

  Future<void> _connect() async {
    if (_apiKeyController.text.trim().isEmpty) {
      setState(() => _error = 'API kalitni kiriting');
      return;
    }

    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final result = await _service.registerDevice(
        _apiKeyController.text.trim(),
        _baseUrlController.text.trim(),
      );

      if (result?['success'] == true) {
        if (mounted) {
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (_) => const HomeScreen()),
          );
        }
      } else {
        setState(() => _error = result?['error'] ?? 'Ulanish xatosi');
      }
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _showApiKeyHelp() {
    showModalBottomSheet(
      context: context,
      backgroundColor: _surface,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) => Padding(
        padding: const EdgeInsets.fromLTRB(24, 20, 24, 36),
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
              'API kalitni qanday olaman?',
              style: TextStyle(
                color: Colors.white,
                fontSize: 18,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 20),
            _helpStep(
              number: '1',
              text: 'getsms.chefit.uz ga kiring',
              icon: Icons.open_in_browser_rounded,
            ),
            const SizedBox(height: 12),
            _helpStep(
              number: '2',
              text: "Ro'yxatdan o'ting",
              icon: Icons.person_add_rounded,
            ),
            const SizedBox(height: 12),
            _helpStep(
              number: '3',
              text: "Sozlamalar > API Key dan nusxa oling",
              icon: Icons.content_copy_rounded,
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                style: ElevatedButton.styleFrom(
                  backgroundColor: _indigo,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  elevation: 0,
                ),
                child: const Text(
                  'Tushunarli',
                  style: TextStyle(fontWeight: FontWeight.w600),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _helpStep({
    required String number,
    required String text,
    required IconData icon,
  }) {
    return Row(
      children: [
        Container(
          width: 32,
          height: 32,
          decoration: BoxDecoration(
            color: _indigo.withValues(alpha: 0.15),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Center(
            child: Text(
              number,
              style: const TextStyle(
                color: _indigoLight,
                fontWeight: FontWeight.w700,
                fontSize: 14,
              ),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Icon(icon, color: _textMuted, size: 18),
        const SizedBox(width: 10),
        Expanded(
          child: Text(
            text,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 14,
              fontWeight: FontWeight.w500,
            ),
          ),
        ),
      ],
    );
  }

  InputDecoration _inputDecoration({
    required String hint,
    required IconData prefixIcon,
  }) {
    return InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Color(0xFF475569), fontSize: 14),
      filled: true,
      fillColor: _surface,
      contentPadding: const EdgeInsets.symmetric(vertical: 18, horizontal: 16),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(16),
        borderSide: BorderSide.none,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(16),
        borderSide: const BorderSide(color: Color(0xFF334155), width: 1),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(16),
        borderSide: const BorderSide(color: _indigo, width: 1.5),
      ),
      prefixIcon: Padding(
        padding: const EdgeInsets.only(left: 4),
        child: Icon(prefixIcon, color: _indigoLight, size: 20),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: _bg,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [_bg, Color(0xFF1E293B)],
            stops: [0.0, 1.0],
          ),
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 40),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // Animated Logo
                  Center(
                    child: AnimatedContainer(
                      duration: const Duration(milliseconds: 600),
                      curve: Curves.easeOutBack,
                      width: _logoVisible ? 80 : 60,
                      height: _logoVisible ? 80 : 60,
                      decoration: BoxDecoration(
                        color: _indigo.withValues(alpha: 0.15),
                        borderRadius: BorderRadius.circular(24),
                        border: Border.all(
                          color: _indigo.withValues(alpha: 0.3),
                          width: 1.5,
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: _indigo.withValues(alpha: 0.2),
                            blurRadius: 24,
                            spreadRadius: 0,
                          ),
                        ],
                      ),
                      child: const Center(
                        child: Icon(
                          Icons.sms_rounded,
                          color: _indigoLight,
                          size: 36,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Title
                  const Text(
                    'SMS Gateway',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 28,
                      fontWeight: FontWeight.w800,
                      letterSpacing: -0.5,
                    ),
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Telefoningizni SMS serverga aylantiring',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: _textMuted,
                      fontSize: 14,
                      fontWeight: FontWeight.w400,
                    ),
                  ),
                  const SizedBox(height: 44),

                  // API Key input
                  TextField(
                    controller: _apiKeyController,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 14,
                      fontFamily: 'monospace',
                    ),
                    decoration: _inputDecoration(
                      hint: 'sk_...',
                      prefixIcon: Icons.key_rounded,
                    ),
                  ),
                  const SizedBox(height: 12),

                  // Server URL input
                  TextField(
                    controller: _baseUrlController,
                    style: const TextStyle(color: Colors.white, fontSize: 14),
                    decoration: _inputDecoration(
                      hint: 'https://...',
                      prefixIcon: Icons.dns_rounded,
                    ),
                  ),
                  const SizedBox(height: 12),

                  // Help link
                  GestureDetector(
                    onTap: _showApiKeyHelp,
                    child: const Text(
                      'API kalitni qanday olaman?',
                      textAlign: TextAlign.right,
                      style: TextStyle(
                        color: _indigoLight,
                        fontSize: 13,
                        fontWeight: FontWeight.w500,
                        decoration: TextDecoration.underline,
                        decorationColor: _indigoLight,
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Error display
                  if (_error != null) ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 14, vertical: 12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFEF4444).withValues(alpha: 0.08),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: const Color(0xFFEF4444).withValues(alpha: 0.3),
                          width: 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          const Icon(
                            Icons.error_outline_rounded,
                            color: Color(0xFFEF4444),
                            size: 16,
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              _error!,
                              style: const TextStyle(
                                color: Color(0xFFFCA5A5),
                                fontSize: 13,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                  ],

                  // Connect button
                  SizedBox(
                    height: 54,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _connect,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _indigo,
                        disabledBackgroundColor:
                            _indigo.withValues(alpha: 0.5),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        elevation: 0,
                        shadowColor: Colors.transparent,
                      ),
                      child: AnimatedSwitcher(
                        duration: const Duration(milliseconds: 200),
                        child: _isLoading
                            ? const SizedBox(
                                key: ValueKey('loading'),
                                width: 22,
                                height: 22,
                                child: CircularProgressIndicator(
                                  color: Colors.white,
                                  strokeWidth: 2.5,
                                ),
                              )
                            : const Text(
                                key: ValueKey('text'),
                                'Ulanish',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w700,
                                  letterSpacing: 0.2,
                                ),
                              ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _apiKeyController.dispose();
    _baseUrlController.dispose();
    super.dispose();
  }
}
