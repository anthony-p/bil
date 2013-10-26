<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 26/10/13
 * Time: 12:03
 * To change this template use File | Settings | File Templates.
 */

class class_probid_user extends npdatabase  {


    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $zip_code;
    /**
     * @var int
     */
    private $state;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $tax_company_name;
    /**
     * @var string
     */
    private $pg_paypal_email;
    /**
     * @var string
     */
    private $pg_paypal_first_name;
    /**
     * @var string
     */
    private $pg_paypal_last_name;
    /**
     * @var int
     */
    private $active;


    /**
     * Constructor
     * @param null $id
     */
    public function __construct( $id = null ) {
        //

    }


    /**
     *
     * Add user
     * @param array $data
     */
    public function addUser ( $data = array() ) {
        //
    }

    /**
     *  Update User
     * @param array $data
     */
    public function updateUser ( $data = array() ) {
        //
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName( $name ) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail ( $email ) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail () {
        return $this->email;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress ( $address) {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress () {
        return $this->address;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity ( $city ) {
        $this->city = $city;
        return $this;
    }

    /**
     * @param $zip_code
     * @return $this
     */
    public function setZipCode ( $zip_code ) {
        $this->zip_code = $zip_code;
        return $this;
    }

    /**
     * @return $this
     */
    public function getZipCode () {
        return $this;
    }

    /**
     * @param $state
     * @return $this
     */
    public function setState ( $state ) {
        $this->state = $state;
        return $this;
    }

    /**
     * @return int
     */
    public function getState () {
        return $this->state;
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry ( $country ) {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry () {
        return $this->country;
    }

    /**
     * @param $tax_company_name
     * @return $this
     */
    public function setTaxCompanyName ( $tax_company_name ) {
        $this->tax_company_name  = $tax_company_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxCompanyName () {
        return $this->tax_company_name;
    }

    /**
     * @param $pg_paypal_email
     * @return $this
     */
    public function setPgPaypalEmail ( $pg_paypal_email ) {
        $this->pg_paypal_email  = $pg_paypal_email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalEmail () {
        return $this->pg_paypal_email;
    }

    /**
     * @param $pg_paypal_first_name
     * @return $this
     */
    public function setPgPaypalFirstName ( $pg_paypal_first_name ) {
        $this->pg_paypal_first_name  = $pg_paypal_first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalFirstName () {
        return $this->pg_paypal_first_name;
    }

    /**
     * @param $pg_paypal_last_name
     * @return $this
     */
    public function setPgPaypalLastName ( $pg_paypal_last_name ) {
        $this->pg_paypal_last_name  = $pg_paypal_last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalLastName () {
        return $this->pg_paypal_last_name;
    }

    /**
     * @param $active
     * @return $this
     */
    public function setActive ( $active ) {
        $this->active  = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive () {
        return $this->active;
    }



} 