<?php

declare(strict_types=1);

namespace roofikk\VkApiBundle\Dto;

final class AddressDto
{
    private ?string $state = NULL;    // обработка кода возврата валидности
    private ?string $inaddr = NULL; // Исходный адрес
    private ?string $outaddr = NULL;    // Полученный адрес
    private ?string $mistake = NULL;    // тип ошибки
    private ?string $delivery = NULL;   // Пригодность для доставки
    private ?int $deliveryCode = NULL;

    private ?string $country = NULL;    // (Российская Федерация)
    private ?string $districtType = NULL;  // (край/респ/обл)
    private ?string $districtName = NULL;  // (Красноярский/Карелия/Ленинградская)
    private ?string $areaType = NULL;  // (р-н)
    private ?string $areaName = NULL;  // (Древлянка)
    private ?string $localityType = NULL;  // (г/с)
    private ?string $localityName = NULL;  // Петрозаводск
    private ?string $streetType = NULL;    // (ул/пр)
    private ?string $streetName = NULL;    // (Лисициной)
    private ?string $houseType = NULL;   // (д)
    private ?string $houseName = NULL;   // Номер дома
    private ?string $letter = NULL;    // Литера
    private ?string $delimiter = NULL; // Дробь
    private ?string $external = NULL;  // Корпус
    private ?string $building = NULL;  // Строение
    private ?string $flat = NULL;  // Помещение/Квартира
    private ?string $boxType = NULL;   // (а/я)
    private ?string $boxName = NULL;   // (45)
    private ?string $militaryType = NULL;  // (в/ч)
    private ?string $militaryName = NULL;  // (1234)

    /*** STATE ***/
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return AddressDto
     */
    public function setState(string $state): AddressDto
    {
        $this->state = $state;
        return $this;
    }

    /*** INADDR ***/
    public function getInaddr(): ?string
    {
        return $this->inaddr;
    }

    /**
     * @param string $inaddr
     * @return AddressDto
     */
    public function setInaddr(string $inaddr): AddressDto
    {
        $this->inaddr = $inaddr;
        return $this;
    }

    /*** OUTADDR ***/
    public function getOutaddr(): ?string
    {
        return $this->outaddr;
    }

    /**
     * @param string $outaddr
     * @return AddressDto
     */
    public function setOutaddr(string $outaddr): AddressDto
    {
        $this->outaddr = $outaddr;
        return $this;
    }

     /*** MISTAKE ***/
    public function getMistake(): ?string
    {
        return $this->mistake;
    }

    /**
     * @param string $mistake
     * @return AddressDto
     */
    public function setMistake(string $mistake): AddressDto
    {
        $this->mistake = $mistake;
        return $this;
    }

    /*** Delivery ***/
    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    /**
     * @param string $delivery
     * @return AddressDto
     */
    public function setDelivery(string $delivery): AddressDto
    {
        $this->delivery = $delivery;
        return $this;
    }

    /*** COUNTRY ***/

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return AddressDto
     */
    public function setCountry(string $country): AddressDto
    {
        $this->country = $country;
        return $this;
    }

    /*** DISTRICT ***/

    /**
     * @return string
     */
    public function getDistrictType(): ?string
    {
        return $this->districtType;
    }

    /**
     * @param string $districtType
     * @return AddressDto
     */
    public function setDistrictType(string $districtType): AddressDto
    {
        $this->districtType = $districtType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDistrictName(): ?string
    {
        return $this->districtName;
    }

    /**
     * @param string $districtName
     * @return AddressDto
     */
    public function setDistrictName(string $districtName): AddressDto
    {
        $this->districtName = $districtName;
        return $this;
    }

   /*** AREA ***/

    /**
     * @return string
     */
    public function getAreaType(): ?string
    {
        return $this->areaType;
    }

    /**
     * @param string $areaType
     * @return AddressDto
     */
    public function setAreaType(string $areaType): AddressDto
    {
        $this->areaType = $areaType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAreaName(): ?string
    {
        return $this->areaName;
    }

    /**
     * @param string $areaName
     * @return AddressDto
     */
    public function setAreaName(string $areaName): AddressDto
    {
        $this->areaName = $areaName;
        return $this;
    }

    /*** LOCALITY ***/

    /**
     * @return string
     */
    public function getLocalityType(): ?string
    {
        return $this->localityType;
    }

    /**
     * @param string $localityType
     * @return AddressDto
     */
    public function setLocalityType(string $localityType): AddressDto
    {
        $this->localityType = $localityType;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalityName(): ?string
    {
        return $this->localityName;
    }

    /**
     * @param string $localityName
     * @return AddressDto
     */
    public function setLocalityName(string $localityName): AddressDto
    {
        $this->localityName = $localityName;
        return $this;
    }

    /*** STREET ***/

    /**
     * @return string
     */
    public function getStreetType(): ?string
    {
        return $this->streetType;
    }

    /**
     * @param string $streetType
     * @return AddressDto
     */
    public function setStreetType(string $streetType): AddressDto
    {
        $this->streetType = $streetType;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @param string $streetName
     * @return AddressDto
     */
    public function setStreetName(string $streetName): AddressDto
    {
        $this->streetName = $streetName;
        return $this;
    }

    /*** HOUSE ***/

    /**
     * @return string
     */
    public function getHouseType(): ?string
    {
        return $this->houseType;
    }

    /**
     * @param string $houseType
     * @return AddressDto
     */
    public function setHouseType(string $houseType): AddressDto
    {
        $this->houseType = $houseType;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseName(): ?string
    {
        return $this->houseName;
    }

    /**
     * @param string $houseName
     * @return AddressDto
     */
    public function setHouseName(string $houseName): AddressDto
    {
        $this->houseName = $houseName;
        return $this;
    }

    /*** LETTER ***/

    /**
     * @return string
     */
    public function getLetter(): ?string
    {
        return $this->letter;
    }

    /**
     * @param string $letter
     * @return AddressDto
     */
    public function setLetter(string $letter): AddressDto
    {
        $this->letter = $letter;
        return $this;
    }

    /*** DELIMITED ***/

    /**
     * @return string
     */
    public function getDelimited(): ?string
    {
        return $this->delimited;
    }

    /**
     * @param string $letter
     * @return AddressDto
     */
    public function setDelimited(string $delimited): AddressDto
    {
        $this->delimited = $delimited;
        return $this;
    }

    /*** EXTERNAL ***/

    /**
     * @return string
     */
    public function getExternal(): ?string
    {
        return $this->external;
    }

    /**
     * @param string $external
     * @return AddressDto
     */
    public function setExternal(string $external): AddressDto
    {
        $this->external = $external;
        return $this;
    }

    /*** BUILDING ***/

    /**
     * @return string
     */
    public function getBuilding(): ?string
    {
        return $this->building;
    }

    /**
     * @param string $building
     * @return AddressDto
     */
    public function setBuilding(string $building): AddressDto
    {
        $this->building = $building;
        return $this;
    }

    /*** FLAT ***/

    /**
     * @return string
     */
    public function getFlat(): ?string
    {
        return $this->flat;
    }

    /**
     * @param string $flat
     * @return AddressDto
     */
    public function setFlat(string $flat): AddressDto
    {
        $this->flat = $flat;
        return $this;
    }

    /*** BOX ***/

    /**
     * @return string
     */
    public function getBoxType(): ?string
    {
        return $this->boxType;
    }

    /**
     * @param string $boxType
     * @return AddressDto
     */
    public function setBoxType(string $boxType): AddressDto
    {
        $this->boxType = $boxType;
        return $this;
    }

    /**
     * @return string
     */
    public function getBoxName(): ?string
    {
        return $this->boxName;
    }

    /**
     * @param string $boxName
     * @return AddressDto
     */
    public function setBoxName(string $boxName): AddressDto
    {
        $this->boxName = $boxName;
        return $this;
    }
}