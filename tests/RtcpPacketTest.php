<?php

namespace Tests\Webrtc\RTCP;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Webrtc\Exception\InvalidArgumentException;
use Webrtc\RTCP\Exception\RtcpPacketException;
use Webrtc\RTCP\RtcpByePacket;
use Webrtc\RTCP\RtcpPacket;
use PHPUnit\Framework\TestCase;
use Webrtc\RTCP\RtcpPsfbPacket;
use Webrtc\RTCP\RtcpReceiverInfo;
use Webrtc\RTCP\RtcpRrPacket;
use Webrtc\RTCP\RtcpRtpfbPacket;
use Webrtc\RTCP\RtcpSdesPacket;
use Webrtc\RTCP\RtcpSenderInfo;
use Webrtc\RTCP\RtcpSourceInfo;
use Webrtc\RTCP\RtcpSrPacket;
use Webrtc\RTCP\RtcpUtility;

#[UsesClass(RtcpByePacket::class)]
#[UsesClass(RtcpPsfbPacket::class)]
#[UsesClass(RtcpSdesPacket::class)]
#[UsesClass(RtcpSrPacket::class)]
#[UsesClass(RtcpReceiverInfo::class)]
#[UsesClass(RtcpSenderInfo::class)]
#[UsesClass(RtcpSourceInfo::class)]
#[UsesClass(RtcpUtility::class)]
#[UsesClass(RtcpRrPacket::class)]
#[UsesClass(RtcpRtpfbPacket::class)]
#[CoversClass(RtcpPacket::class)]
class RtcpPacketTest extends TestCase {

    public function testBye(): void {
        $data = $this->load("rtcp_bye.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpByePacket::class, $packet);
        $this->assertEquals([2924645187], $packet->getSources());
        $this->assertEquals($data, $packet->encode());
    }

    public function testByeInvalid(): void {
        $data = $this->load("rtcp_bye_invalid.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP bye length is invalid");
        RtcpPacket::decode($data);
    }

    public function testByeNoSources(): void {
        $data = $this->load("rtcp_bye_no_sources.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpByePacket::class, $packet);
        $this->assertEquals([], $packet->getSources());
        $this->assertEquals($data, $packet->encode());
    }

    public function testByeOnlyPadding(): void {
        $data = $this->load("rtcp_bye_padding.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpByePacket::class, $packet);
        $this->assertEquals([], $packet->getSources());
        $this->assertEquals("\x80\xcb\x00\x00", $packet->encode());
    }

    public function testByeOnlyPaddingZero(): void {
        $data = substr($this->load("rtcp_bye_padding.bin"), 0, 4) . "\x00\x00\x00\x00";

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP packet padding length is invalid");
        RtcpPacket::decode($data);
    }

    public function testPsfbInvalid(): void {
        $data = $this->load("rtcp_psfb_invalid.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP payload-specific feedback length is invalid");
        RtcpPacket::decode($data);
    }

    public function testPsfbPli(): void {
        $data = $this->load("rtcp_psfb_pli.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpPsfbPacket::class, $packet);
        $this->assertEquals(1, $packet->getFmt());
        $this->assertEquals(1414554213, $packet->getSsrc());
        $this->assertEquals(587284409, $packet->getMediaSsrc());
        $this->assertEquals("", $packet->getFci());
        $this->assertEquals($data, $packet->encode());
    }

    public function testRr(): void {
        $data = $this->load("rtcp_rr.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpRrPacket::class, $packet);
        $this->assertEquals(817267719, $packet->getSsrc());
        $this->assertEquals(1200895919, $packet->getReports()[0]->getSsrc());
        $this->assertEquals(0, $packet->getReports()[0]->getFractionLost());
        $this->assertEquals(0, $packet->getReports()[0]->getPacketsLost());
        $this->assertEquals(630, $packet->getReports()[0]->getHighestSequence());
        $this->assertEquals(1906, $packet->getReports()[0]->getJitter());
        $this->assertEquals(0, $packet->getReports()[0]->getLsr());
        $this->assertEquals(0, $packet->getReports()[0]->getDlsr());
        $this->assertEquals($data, $packet->encode());
    }

    public function testRrInvalid(): void {
        $data = $this->load("rtcp_rr_invalid.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP receiver report length is invalid");
        RtcpPacket::decode($data);
    }

    public function testRrTruncated(): void {
        $data = $this->load("rtcp_rr.bin");

        for ($length = 1; $length < 4; $length++) {
            $this->expectException(RtcpPacketException::class);
            $this->expectExceptionMessage("RTCP packet length is less than 4 bytes");
            RtcpPacket::decode(substr($data, 0, $length));
        }

        for ($length = 4; $length < 32; $length++) {
            $this->expectException(RtcpPacketException::class);
            $this->expectExceptionMessage("RTCP packet is truncated");
            RtcpPacket::decode(substr($data, 0, $length));
        }
    }

    public function testSdes(): void {
        $data = $this->load("rtcp_sdes.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpSdesPacket::class, $packet);
        $this->assertEquals(1831097322, $packet->getChunks()[0]->getSsrc());
        $this->assertEquals([1, "{63f459ea-41fe-4474-9d33-9707c9ee79d1}"], $packet->getChunks()[0]->getItems()[0]);
        $this->assertEquals($data, $packet->encode());
    }

    public function testSdesItemTruncated(): void {
        $data = $this->load("rtcp_sdes_item_truncated.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP SDES item is truncated");
        RtcpPacket::decode($data);
    }

    public function testSdesSourceTruncated(): void {
        $data = $this->load("rtcp_sdes_source_truncated.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP SDES source is truncated");
        RtcpPacket::decode($data);
    }

    public function testSr(): void {
        $data = $this->load("rtcp_sr.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpSrPacket::class, $packet);
        $this->assertEquals(1831097322, $packet->getSsrc());
        // Compare the wire-level halves independently: the combined unsigned value is
        // larger than PHP_INT_MAX and an integer literal would become a lossy float.
        $this->assertSame(3729147739, $packet->getSenderInfo()->getNtpTimestampHigh());
        $this->assertSame(354025564, $packet->getSenderInfo()->getNtpTimestampLow());
        $this->assertEquals(1722342718, $packet->getSenderInfo()->getRtpTimestamp());
        $this->assertEquals(269, $packet->getSenderInfo()->getPacketCount());
        $this->assertEquals(13557, $packet->getSenderInfo()->getOctetCount());
        $this->assertCount(1, $packet->getReports());
        $this->assertEquals(2398654957, $packet->getReports()[0]->getSsrc());
        $this->assertEquals(0, $packet->getReports()[0]->getFractionLost());
        $this->assertEquals(0, $packet->getReports()[0]->getPacketsLost());
        $this->assertEquals(246, $packet->getReports()[0]->getHighestSequence());
        $this->assertEquals(127, $packet->getReports()[0]->getJitter());
        $this->assertEquals(0, $packet->getReports()[0]->getLsr());
        $this->assertEquals(0, $packet->getReports()[0]->getDlsr());
        $this->assertEquals($data, $packet->encode());
    }

    public function testSrInvalid(): void {
        $data = $this->load("rtcp_sr_invalid.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP sender report length is invalid");
        RtcpPacket::decode($data);
    }

    public function testRtpfb(): void {
        $data = $this->load("rtcp_rtpfb.bin");
        $packets = RtcpPacket::decode($data);

        $this->assertCount(1, $packets);

        $packet = $packets[0];
        $this->assertInstanceOf(RtcpRtpfbPacket::class, $packet);
        $this->assertEquals(1, $packet->getFmt());
        $this->assertEquals(2336520123, $packet->getSsrc());
        $this->assertEquals(4145934052, $packet->getMediaSsrc());
        $this->assertEquals([12, 32, 39, 54, 76, 110, 123, 142, 183, 187, 223, 236, 271, 292], $packet->getLost());
        $this->assertEquals($data, $packet->encode());
    }

    public function testRtpfbInvalid(): void {
        $data = $this->load("rtcp_rtpfb_invalid.bin");

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP RTP feedback length is invalid");
        RtcpPacket::decode($data);
    }

    public function testCompound(): void {
        $data = $this->load("rtcp_sr.bin") . $this->load("rtcp_sdes.bin");

        $packets = RtcpPacket::decode($data);
        $this->assertCount(2, $packets);
        $this->assertInstanceOf(RtcpSrPacket::class, $packets[0]);
        $this->assertInstanceOf(RtcpSdesPacket::class, $packets[1]);
    }

    public function testBadVersion(): void {
        $data = "\xc0" . substr($this->load("rtcp_rr.bin"), 1);

        $this->expectException(RtcpPacketException::class);
        $this->expectExceptionMessage("RTCP packet has invalid version");
        RtcpPacket::decode($data);
    }

    private function load(string $filename): string {
        return file_get_contents(__DIR__ . "/fixture/$filename");
    }
}
