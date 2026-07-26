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
    const RTCP_HEADER_LENGTH = 4;

    // RTCP packet types
    const RTCP_SR = 200;
    const RTCP_RR = 201;
    const RTCP_SDES = 202;
    const RTCP_BYE = 203;
    const RTCP_RTPFB = 205;
    const RTCP_PSFB = 206;

    // RTCP Feedback Message Types
    const RTCP_RTPFB_NACK = 1;

    // RTCP Payload-Specific Feedback Messages
    const RTCP_PSFB_PLI = 1;
    const RTCP_PSFB_SLI = 2;
    const RTCP_PSFB_RPSI = 3;
    const RTCP_PSFB_APP = 15;
}
