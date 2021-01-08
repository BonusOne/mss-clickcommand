<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClickStatisticsRepository")
 * @ORM\Table(name="click_statistics", indexes={
 *     @ORM\Index(name="index_id", columns={"id"}),
 *     @ORM\Index(name="index_redirect_id", columns={"redirect_id"}),
 *     @ORM\Index(name="index_smart", columns={"id_smart_insertion"}),
 *     @ORM\Index(name="index_company", columns={"id_company"}),
 *     @ORM\Index(name="index_timestamp", columns={"timestamp"}),
 *     @ORM\Index(name="index_date", columns={"date"}),
 *     @ORM\Index(name="index_browser", columns={"browser"}),
 *     @ORM\Index(name="index_operating_system", columns={"operating_system"}),
 *     @ORM\Index(name="index_device", columns={"device"}),
 * })
 */
class ClickStatistics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", options={"unsigned":true, "default":0})
     */
    private $redirect_id;

    /**
     * @ORM\Column(type="bigint", nullable=true, options={"unsigned":true, "default":0})
     */
    private $id_smart_insertion;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":2})
     */
    private $id_company;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
     */
    private $ipv4;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true, options={"default":NULL})
     */
    private $referer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
     */
    private $browser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
     */
    private $operating_system;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
     */
    private $device;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
     */
    private $rendering_engine;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true, options={"default":NULL})
     */
    private $useragent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRedirectId(): ?string
    {
        return $this->redirect_id;
    }

    public function setRedirectId(string $redirect_id): self
    {
        $this->redirect_id = $redirect_id;

        return $this;
    }

    public function getIdSmartInsertion(): ?string
    {
        return $this->id_smart_insertion;
    }

    public function setIdSmartInsertion(?string $id_smart_insertion): self
    {
        $this->id_smart_insertion = $id_smart_insertion;

        return $this;
    }

    public function getIdCompany(): ?int
    {
        return $this->id_company;
    }

    public function setIdCompany(int $id_company): self
    {
        $this->id_company = $id_company;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(?\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getIpv4(): ?string
    {
        return $this->ipv4;
    }

    public function setIpv4(?string $ipv4): self
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(?string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operating_system;
    }

    public function setOperatingSystem(?string $operating_system): self
    {
        $this->operating_system = $operating_system;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getRenderingEngine(): ?string
    {
        return $this->rendering_engine;
    }

    public function setRenderingEngine(?string $rendering_engine): self
    {
        $this->rendering_engine = $rendering_engine;

        return $this;
    }

    public function getUseragent(): ?string
    {
        return $this->useragent;
    }

    public function setUseragent(?string $useragent): self
    {
        $this->useragent = $useragent;

        return $this;
    }

}
