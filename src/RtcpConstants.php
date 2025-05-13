<?php

/**
 * This file is part of the PHP WebRTC package.
 *
 * (c) Amin Yazdanpanah <https://www.aminyazdanpanah.com/#contact>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webrtc\RTCP;

class RtcpConstants
{
    // Header lengths
    const int RTCP_HEADER_LENGTH = 4;

    // RTCP packet types
    const int RTCP_SR = 200;
    const int RTCP_RR = 201;
    const int RTCP_SDES = 202;
    const int RTCP_BYE = 203;
    const int RTCP_RTPFB = 205;
    const int RTCP_PSFB = 206;

    // RTCP Feedback Message Types
    const int RTCP_RTPFB_NACK = 1;

    // RTCP Payload-Specific Feedback Messages
    const int RTCP_PSFB_PLI = 1;
    const int RTCP_PSFB_SLI = 2;
    const int RTCP_PSFB_RPSI = 3;
    const int RTCP_PSFB_APP = 15;
}
