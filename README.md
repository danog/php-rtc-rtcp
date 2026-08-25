# RTCP Library for PHP


[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-BSD-blue.svg)](LICENSE)

A pure PHP implementation of RTP Control Protocol (RTCP) packet parsing and generation, compliant with RFC 3550 and related specifications.

## About this fork

This is the `danog/php-rtc-rtcp` fork used by MadelineProto. It targets PHP 8.2+ and fixes sender-report comparisons for unsigned 64-bit NTP timestamps.

All internal Composer dependencies use their `danog/php-rtc-*` package names directly, so installing a component selects the maintained danog packages throughout the dependency graph.

##  Features

- Complete RTCP packet support:
  - Sender Reports (SR)
  - Receiver Reports (RR)
  - Source Description (SDES)
  - Goodbye (BYE)
  - Application-Defined (APP)
  - Transport Layer Feedback (RTPFB)
  - Payload-Specific Feedback (PSFB)
- Zero dependencies


## Requirements

- PHP ≥ 8.2

## Documentation

This package is part of the PHP WebRTC library. For complete documentation, examples, and API reference, visit:

[PHP WebRTC Documentation](https://www.quasarstream.com/php-webrtc)

## Credits

### Authors

- **Amin Yazdanpanah**  
  - Website: [aminyazdanpanah.com](https://www.aminyazdanpanah.com)
  - Email: [github@aminyazdanpanah.com](mailto:github@aminyazdanpanah.com)

- **Sana Moniri**  
  - GtiHub: [sanamoniri](https://github.com/sanamoniri)

## Reporting Issues

Found a bug? Please report it on our [issues](https://github.com/php-webrtc/rtcp/issues).

## License

BSD 3-Clause License. See [LICENSE](LICENSE) for details.

## References
- [RFC 3550 - RTP: A Transport Protocol for Real-Time Applications](https://tools.ietf.org/html/rfc3550)

- [RFC 4585 - Extended RTP Profile for RTCP-Based Feedback](https://tools.ietf.org/html/rfc4585)

- [RFC 5506 - Reduced-Size RTCP](https://tools.ietf.org/html/rfc5506)
