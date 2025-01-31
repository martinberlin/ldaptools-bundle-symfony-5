<?php
/**
 * This file is part of the LdapToolsBundle package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LdapTools\Bundle\LdapToolsBundle\Security\User;

use LdapTools\Object\LdapObject;
use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Security\Core\User\AdvancedUserInterface; // No more in Symfony 5

/**
 * Represents a user from LDAP.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 * \Serializable is deprecated, we use directly serialize()
 */
class LdapUser extends LdapObject implements LdapUserInterface, UserInterface
{
    /**
     * @var array The Symfony roles for this user.
     */
    protected $roles = [];

    public function __construct()
    {
        parent::__construct([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt() : string
    {
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword() : string
    {
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles() : array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        $role = strtoupper($role);

        if (in_array($role, $this->roles)) {
            $this->roles = array_diff($this->roles, [$role]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername() : string
    {
        return $this->get('username');
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username)
    {
        return $this->set('username', $username);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        if (!$this->has('accountExpirationDate') || $this->get('accountExpirationDate') === false) {
            $result = true;
        } elseif ($this->get('accountExpirationDate') instanceof \DateTime) {
            $result = ($this->get('accountExpirationDate') > new \DateTime());
        } else {
            $result = (bool) $this->get('accountExpirationDate');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->has('locked') ? !$this->get('locked') : true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->has('passwordMustChange') ? !$this->get('passwordMustChange') : true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->has('enabled') ? $this->get('enabled') : true;
    }

    /**
     * {@inheritdoc}
     */
    public function getLdapGuid()
    {
        return $this->get('guid');
    }

    /**
     * {@inheritdoc}
     */
    public function setLdapGuid($guid)
    {
        return $this->set('guid', $guid);
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->has('groups') ? $this->get('groups') : [];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->attributes,
            $this->type,
            $this->roles
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->attributes, $this->type, $this->roles) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }
}
