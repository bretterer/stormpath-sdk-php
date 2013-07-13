<?php

namespace Stormpath\Resource;

use Stormpath\Resource\AbstractResource;
use Stormpath\Service\StormpathService;
use Stormpath\Collections\ResourceCollection;
use Zend\Http\Client;
use Zend\Json\Json;

class Application extends AbstractResource
{
    protected $_url = 'https://api.stormpath.com/v1/applications';
    protected $_expandString = 'tenant';

    protected $name;
    protected $description;
    protected $status;
    protected $tenant;
    protected $accounts;
    protected $groups;
    protected $loginAttempts;
    protected $passwordResetTokens;

    public function getName()
    {
        $this->_load();
        return $this->name;
    }

    public function setName($value)
    {
        $this->_load();
        $this->name = $value;
        return $this;
    }

    public function getDescription()
    {
        $this->_load();
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->_load();
        $this->description = $value;
        return $this;
    }

    public function getStatus()
    {
        $this->_load();
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->_load();
        $this->status = $value;
        return $this;
    }

    public function setTenant(Tenant $value)
    {
        $this->_load();
        $this->tenant = $value;
        return $this;
    }

    public function getTenant()
    {
        $this->_load();
        return $this->tenant;
    }

    public function setAccounts(ResourceCollection $value)
    {
        $this->_load();
        $this->accounts = $value;
        return $this;
    }

    public function getAccounts()
    {
        $this->_load();
        return $this->accounts;
    }

    public function setGroups(ResourceCollection $value)
    {
        $this->_load();
        $this->groups = $value;
        return $this;
    }

    public function getGroups()
    {
        $this->_load();
        return $this->groups;
    }

    public function setLoginAttempts(ResourceCollection $value)
    {
        $this->_load();
        $this->loginAttempts = $value;
        return $this;
    }

    public function getLoginAttempts()
    {
        $this->_load();
        return $this->loginAttempts;
    }

    public function setPasswordResetTokens(ResourceCollection $value)
    {
        $this->_load();
        $this->passwordResetTokens = $value;
        return $this;
    }

    public function getPasswordResetTokens()
    {
        $this->_load();
        return $this->passwordResetTokens;
    }

    public function exchangeArray($data)
    {
        $eager = $this->getResourceManager()->getExpandReferences();
        $this->getResourceManager()->setExpandReferences(false);

        $this->setHref(isset($data['href']) ? $data['href']: null);
        $this->setName(isset($data['name']) ? $data['name']: null);
        $this->setDescription(isset($data['description']) ? $data['description']: null);
        $this->setStatus(isset($data['status']) ? $data['status']: null);

        if ($eager) {
            // If this resource was fetched with eager loading store the retrieved data in the cache then
            // fetch the object from the cache.
            $this->getResourceManager()->getCache()->setItem('Stormpath\Resource\Tenant' . strrpos($data['tenant']['href'], '/') + 1, $data['tenant']);
            $tenant = $this->getResourceManager()->find('Stormpath\Resource\Tenant', strrpos($data['tenant']['href'], '/') + 1, false);
        } else {
            $tenant = new \Stormpath\Resource\Tenant;
            $tenant->_lazy($this->getResourceManager(), substr($data['tenant']['href'], strrpos($data['tenant']['href'], '/') + 1));
        }
        $this->setTenant($tenant);

        $this->setAccounts(new ResourceCollection($this->getResourceManager(), 'Stormpath\Resource\Account', $data['accounts']['href']));
        $this->setGroups(new ResourceCollection($this->getResourceManager(), 'Stormpath\Resource\Group', $data['groups']['href']));
        $this->setLoginAttempts(new ResourceCollection($this->getResourceManager(), 'Stormpath\Resource\LoginAttempt', $data['loginAttempts']['href']));
        $this->setPasswordResetTokens(new ResourceCollection($this->getResourceManager(), 'Stormpath\Resource\PasswordResetToken', $data['passwordResetTokens']['href']));
    }

    /**
     * GetArrayCopy only returns those properties which can be updated
     */
    public function getArrayCopy()
    {
        $this->_load();

        return array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
        );
    }
}
