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

use Webrtc\RTCP\Exception\RtcpPacketException;

/**
 * RTCP Sender Report Information
 *
 * Contains timing and statistics information sent in SR packets.
 * Includes:
 * - NTP timestamp (64 bits)
 * - RTP timestamp (32 bits)
 * - Packet count (32 bits)
 * - Octet count (32 bits)
 *
 * Part of RTCP Sender Report (SR) packet defined in RFC 3550 section 6.4.1
 */
readonly class RtcpSenderInfo implements RtcpPacketInterface
{
    /**
     * Constructs new sender info block
     *
     * @param int $rtpTimestamp Media timestamp
     * @param int $packetCount Total packets sent
     * @param int $octetCount Total payload bytes sent
     */
    public function __construct(
        private int $ntpTimestampHigh,
        private int $ntpTimestampLow,
        private int    $rtpTimestamp,
        private int    $packetCount,
        private int    $octetCount
    ) {
    }

    /**
     * Encodes sender info into binary format
     *
     * @return string 20-byte binary sender info block
     */
    public function encode(): string
    {
        return pack(
            'NNNNNN',
            $this->ntpTimestampHigh,
            $this->ntpTimestampLow,
            $this->rtpTimestamp,
            $this->packetCount,
            $this->octetCount
        );
    }

    /**
     * Decodes binary data into sender info
     *
     * @param string $data 20-byte sender info block
     * @param int|null $count Unused (interface compatibility)
     * @return self New RtcpSenderInfo instance
     * @throws RtcpPacketException If data length is not 20 bytes
     */
    public static function decode(string $data, ?int $count = null): self
    {
        if (strlen($data) != 20) {
            throw new RtcpPacketException("Sender information block length is invalid");
        }

        $unpacked = unpack('NntpHigh/NntpLow/NrtpTimestamp/NpacketCount/NoctetCount', $data);

        return new self(
            $unpacked['ntpHigh'],
            $unpacked['ntpLow'],
            $unpacked['rtpTimestamp'],
            $unpacked['packetCount'],
            $unpacked['octetCount']
        );
    }

    /**
     * Get NTP timestamp
     */
    public function getNtpTimestampHigh(): int
    {
        return $this->ntpTimestampHigh;
    }

    public function getNtpTimestampLow(): int
    {
        return $this->ntpTimestampLow;
    }

    /**
     * Get RTP timestamp
     *
     * @return int 32-bit media timestamp
     */
    public function getRtpTimestamp(): int
    {
        return $this->rtpTimestamp;
    }

    /**
     * Get packet count
     *
     * @return int Total packets sent
     */
    public function getPacketCount(): int
    {
        return $this->packetCount;
    }

    /**
     * Get octet count
     *
     * @return int Total payload bytes sent
     */
    public function getOctetCount(): int
    {
        return $this->octetCount;
    }

    public function __toString(): string
    {
        return __CLASS__;
    }
}