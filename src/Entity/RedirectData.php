<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RedirectDataRepository")
 * @ORM\Table(name="redirect_data", indexes={
 *     @ORM\Index(name="index_id", columns={"id"}),
 *     @ORM\Index(name="index_smart", columns={"id_smart_campaign"}),
 *     @ORM\Index(name="index_trackly", columns={"id_trackly_campaign"}),
 *     @ORM\Index(name="index_sataku", columns={"id_sataku_campaign"}),
 *     @ORM\Index(name="index_date", columns={"date"}),
 *     @ORM\Index(name="index_search_redirect", columns={"id","deleted","name","lp","id_smart_campaign","id_trackly_campaign","id_sataku_campaign"}),
 * })
 */
class RedirectData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $lp;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $url;

    /**
     * @ORM\Column(type="bigint", nullable=true, options={"unsigned":true, "default":0})
     */
    private $id_smart_campaign;

    /**
     * @ORM\Column(type="bigint", nullable=true, options={"unsigned":true, "default":0})
     */
    private $id_trackly_campaign;

    /**
     * @ORM\Column(type="bigint", nullable=true, options={"unsigned":true, "default":0})
     */
    private $id_sataku_campaign;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $date;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $deleted;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true, "default":0})
     */
    private $using_smart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLp(): ?int
    {
        return $this->lp;
    }

    public function setLp(int $lp): self
    {
        $this->lp = $lp;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIdSmartCampaign(): ?string
    {
        return $this->id_smart_campaign;
    }

    public function setIdSmartCampaign(?string $id_smart_campaign): self
    {
        $this->id_smart_campaign = $id_smart_campaign;

        return $this;
    }

    public function getIdTracklyCampaign(): ?string
    {
        return $this->id_trackly_campaign;
    }

    public function setIdTracklyCampaign(?string $id_trackly_campaign): self
    {
        $this->id_trackly_campaign = $id_trackly_campaign;

        return $this;
    }

    public function getIdSatakuCampaign(): ?string
    {
        return $this->id_sataku_campaign;
    }

    public function setIdSatakuCampaign(?string $id_sataku_campaign): self
    {
        $this->id_sataku_campaign = $id_sataku_campaign;

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

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getUsingSmart(): ?int
    {
        return $this->using_smart;
    }

    public function setUsingSmart(int $using_smart): self
    {
        $this->using_smart = $using_smart;

        return $this;
    }
}
