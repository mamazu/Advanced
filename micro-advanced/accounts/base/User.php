<?php
/**
 * Advanced microFramework
 * -
 * @copyright Copyright (c) 2019 Advanced microFramework
 * @author    Advanced microFramework Team (Denzel Code, Soull Darknezz)
 */

namespace advanced\accounts\base;

use advanced\accounts\Guest;
use advanced\Bootstrap;
use advanced\exceptions\UserException;

/**
 * User abstract class
 */
abstract class User {

    protected $data = [ 'birth_date' => '0/0/2000' ];

    protected $authData = [];

    public function getDataArray() : array {
        return $this->data;
    }

    public function setDataArray(array $data) {
        foreach ($data as $key => $value) $this->data[$key] = $value;
    }

    public function get(string $data) {
        return $this->data[$data];
    }

    public function setData(string $name, $value) {
        $this->data[$name] = $value;
    }

    public function getAuthData(string $data) {
        return $this->authData[$data];
    }

    public function setAuthData(string $name, $value) {
        $this->authData[$name] = $value;
    }

    public function getAuthDataArray() : array {
        return $this->authData;
    }

    public function setAuthDataArray(array $authData = []) {
        foreach ($authData as $key => $value) $this->authData[$key] = $value;
    }

    /**
     * @return int
     */
    public function getId() : int {
        return (int) $this->get('id');
    }

    /**
     * @return string
     */
    public function getName() : string {
        return (string) $this->get('username');
    }

    /**
     * @return string
     */
    public function getDisplayName() : string {
        return (string) $this->get('display_name');
    }

    /**
     * @return string
     */
    public function getGender() : string {
        return (string) $this->get('gender');
    }

    /**
     * @return string
     */
    public function getMail() : string {
        return (string) $this->get('mail');
    }

    /**
     * @return string
     */
    public function getPassword() : string {
        return (string) $this->get('password');
    }

    /**
     * @return int
     */
    public function getAccountCreated() : int {
        return (int) $this->get('account_created');
    }

    /**
     * @return bool
     */
    public static function isValidName(string $name) : bool {
        $config = Bootstrap::getConfig();

        $userCheck = preg_match('/^(?=.*[a-zA-Z]{1,})(?=.*[\d]{0,})[a-zA-Z0-9=?!@:.-]{' . $config->get('sign_up')['min_characters'] . ',' . $config->get('sign_up')['max_characters'] . '}$/', $name);

        $isMail = filter_var($name, FILTER_VALIDATE_EMAIL);

        $invalidNames = ['Guest', 'guest', $config->get('web')['name'], (new Guest())->getName()];

        if (!$userCheck || $isMail || in_array($name, $invalidNames)) return false;

        return true;
    }

    public static function isValidDisplayName(string $name) : bool {
        return !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name);
    }

    /**
     * @return string
     */
    public static function generateToken(int $length = 40) : string {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+';
        $token = "";

        for ($i = 0; $i < $length; $i++) $token .= $characters[mt_rand(0, strlen($characters) - 1)];

        return $token;
    }

    /**
     * @return bool
     */
    public static function isValidMail(string $mail) : bool {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @return bool
     */
    abstract protected function delete() : bool;

    /**
     * @return bool
     */
    abstract protected function authenticate(bool $cookie = false, array $data = []) : bool;

    /**
     * @return bool
     */
    abstract protected function create() : bool;

    /**
     * @return bool
     */
    abstract protected function exists() : bool;

    abstract protected function set(array $data) : void;

    /**
     * @return array
     */
    abstract protected function getAll() : array;
}

