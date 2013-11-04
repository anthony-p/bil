<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 26/10/13
 * Time: 12:03
 * To change this template use File | Settings | File Templates.
 */

class class_probid_user extends database  {


    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $user_id;
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
        if( $id ) {
            $this->user_id = $id;
            $this->load();
        }
    }


    /**
     *
     * Add user
     * @param array $data
     */
    public function addUser ( $data = array() ) {

        $this->setName($data['fname'].' '. $data['lname'])
            ->setEmail($data['email'])
            ->setUserId($data['user_id']);

        $this->query("INSERT INTO `probid_users` (`name`, `email`, `bl2_user_id`) VALUES ('".$this->getName()."', '". $this->getEmail()."', ".$this->getUserId().")");
        $this->id = $this->insert_id();
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
     * @return class_probid_user
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
     * @return class_probid_user
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
     * @return class_probid_user
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
     * @return class_probid_user
     */
    public function setCity ( $city )
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $zip_code
     * @return class_probid_user
     */
    public function setZipCode ( $zip_code ) {
        $this->zip_code = $zip_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode () {
        return $this->zip_code;
    }

    /**
     * @param $state
     * @return class_probid_user
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
     * @return class_probid_user
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
     * @return class_probid_user
     */
    public function setTaxCompanyName ( $tax_company_name ) {
        $this->tax_company_name  = $tax_company_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxCompanyName ()
    {
        return $this->tax_company_name;
    }

    /**
     * @param $pg_paypal_email
     * @return class_probid_user
     */
    public function setPgPaypalEmail ( $pg_paypal_email )
    {
        $this->pg_paypal_email  = $pg_paypal_email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalEmail ()
    {
        return $this->pg_paypal_email;
    }

    /**
     * @param $pg_paypal_first_name
     * @return class_probid_user
     */
    public function setPgPaypalFirstName ( $pg_paypal_first_name )
    {
        $this->pg_paypal_first_name  = $pg_paypal_first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalFirstName ()
    {
        return $this->pg_paypal_first_name;
    }

    /**
     * @param $pg_paypal_last_name
     * @return class_probid_user
     */
    public function setPgPaypalLastName ( $pg_paypal_last_name )
    {
        $this->pg_paypal_last_name  = $pg_paypal_last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPgPaypalLastName ()
    {
        return $this->pg_paypal_last_name;
    }

    /**
     * @param $active
     * @return class_probid_user
     */
    public function setActive ( $active )
    {
        $this->active  = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive ()
    {
        return $this->active;
    }

    /**
     * @param $user_id
     * @return class_probid_user
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function load ()
    {
        $row = $this->get_sql_row("SELECT * FROM probid_users WHERE bl2_user_id=".$this->user_id);
        $this->fillObject($row);
    }

    private function fillObject( $data )
    {
        $this->id = $data['user_id'];

        $this->setEmail($data['email'])
            ->setName($data['name'])
            ->setUserId($data['user_id'])
            ->setAddress($data['address'])
            ->setCity($data['city'])
            ->setState($data['state'])
            ->setCountry($data['country'])
            ->setZipCode($data['zip_code'])
            ->setPgPaypalEmail($data['pg_paypal_email'])
            ->setPgPaypalFirstName($data['pg_paypal_first_name'])
            ->setPgPaypalLastName($data['pg_paypal_last_name'])
            ->setActive($data['active'])
            ->setUserId($data['user_id'])
            ->setTaxCompanyName($data['tax_company_name']);

    }

    public function update( $data )
    {
        if (! $this->getId())
            return false;

        $this->setEmail($data['email'])
            ->setName($data['fname']." ".$data['lname'])
            ->setUserId($data['user_id'])
            ->setAddress($data['address'])
            ->setCity($data['city'])
            ->setState($data['state'])
            ->setCountry($data['country'])
            ->setZipCode($data['zip_code'])
            ->setPgPaypalEmail($data['pg_paypal_email'])
            ->setPgPaypalFirstName($data['pg_paypal_first_name'])
            ->setPgPaypalLastName($data['pg_paypal_last_name'])
            ->setActive($data['active'])
            ->setTaxCompanyName($data['tax_company_name']);


        $sql = "UPDATE `probid_users` SET `address`='".$this->getAddress()."', `name`='".$this->getName()."', `email`='".$this->getEmail()."', `city`='".$this->getCity()."', `state`='".$this->getState()."', `country`='".$this->getCountry()."', `zip_code`='".$this->getZipCode()."',`pg_paypal_email`='".$this->getPgPaypalEmail()."', `paypal_first_name`='".$this->getPgPaypalFirstName()."',`paypal_last_name`='".$this->getPgPaypalLastName()."', `tax_company_name`='".$this->getTaxCompanyName()."' WHERE `user_id`='". $this->getId()."' ";
        $this->query( $sql );

    }


} 