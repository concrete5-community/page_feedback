<?php

namespace A3020\PageFeedback\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="PageFeedbackForms",
 * )
 */
class Form
{
    /**
     * @ORM\Id @ORM\Column(type="integer", options={"unsigned": true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isEnabled = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $introText;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enableEmailField = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $emailFieldRequired = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $emailRecipient;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enableCaptcha = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enableAutoClose = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enableAcceptTerms = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $acceptTermsText;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $urlsInclude;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $urlsExclude;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $buttonCaption;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the type of feedback form. E.g. 'simple'.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = (bool) $isEnabled;
    }

    /**
     * @return string
     */
    public function getEmailRecipient()
    {
        return $this->emailRecipient;
    }

    /**
     * @param string $emailRecipient
     */
    public function setEmailRecipient($emailRecipient)
    {
        $this->emailRecipient = $emailRecipient;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getButtonCaption()
    {
        return $this->buttonCaption;
    }

    /**
     * @param string $buttonCaption
     */
    public function setButtonCaption($buttonCaption)
    {
        $this->buttonCaption = $buttonCaption;
    }

    /**
     * @return string
     */
    public function getUrlsInclude()
    {
        return $this->urlsInclude;
    }

    /**
     * @param string $urlsInclude
     */
    public function setUrlsInclude($urlsInclude)
    {
        $this->urlsInclude = $urlsInclude;
    }

    /**
     * @return bool
     */
    public function isEmailFieldEnabled()
    {
        return (bool) $this->enableEmailField;
    }

    /**
     * @param bool $enableEmailField
     */
    public function setEnableEmailField($enableEmailField)
    {
        $this->enableEmailField = (bool) $enableEmailField;
    }

    /**
     * @return string
     */
    public function getUrlsExclude()
    {
        return $this->urlsExclude;
    }

    /**
     * @param string $urlsExclude
     */
    public function setUrlsExclude($urlsExclude)
    {
        $this->urlsExclude = $urlsExclude;
    }

    /**
     * @return bool
     */
    public function isEmailFieldRequired()
    {
        return (bool) $this->emailFieldRequired;
    }

    /**
     * @param bool $emailFieldRequired
     */
    public function setEmailFieldRequired($emailFieldRequired)
    {
        $this->emailFieldRequired = (bool) $emailFieldRequired;
    }

    /**
     * @return bool
     */
    public function isCaptchaEnabled()
    {
        return (bool) $this->enableCaptcha;
    }

    /**
     * @param bool $enableCaptcha
     */
    public function setEnableCaptcha($enableCaptcha)
    {
        $this->enableCaptcha = (bool) $enableCaptcha;
    }

    /**
     * @return bool
     */
    public function isAutoCloseEnabled()
    {
        return (bool) $this->enableAutoClose;
    }

    /**
     * @param bool $enableAutoClose
     */
    public function setEnableAutoClose($enableAutoClose)
    {
        $this->enableAutoClose = (bool) $enableAutoClose;
    }

    /**
     * @return bool
     */
    public function isAcceptTermsEnabled()
    {
        return (bool) $this->enableAcceptTerms;
    }

    /**
     * @param bool $enableAcceptTerms
     */
    public function setEnableAcceptTerms($enableAcceptTerms)
    {
        $this->enableAcceptTerms = (bool) $enableAcceptTerms;
    }

    /**
     * @return string
     */
    public function getAcceptTermsText()
    {
        return $this->acceptTermsText;
    }

    /**
     * Strip away the paragraph tags
     *
     * @return string
     */
    public function getAcceptTermsTextForDisplay()
    {
        $text = trim($this->acceptTermsText);

        // Check if string starts with a <p> tag
        if (strpos($text, '<p>') === false) {
            return $text;
        }

        $stripped = substr($text, 3);

        return substr($stripped, 0, -4);
    }

    /**
     * @param string $acceptTermsText
     */
    public function setAcceptTermsText($acceptTermsText)
    {
        $this->acceptTermsText = $acceptTermsText;
    }

    /**
     * @return string
     */
    public function getIntroText()
    {
        return (string) $this->introText;
    }

    /**
     * Get intro text for display.
     *
     * If it only consists of paragraph tags, return an empty string.
     *
     * @return string
     */
    public function getIntroTextForDisplay()
    {
        $text = trim($this->introText);

        if (strlen(strip_tags($text)) === 0) {
            return '';
        }

        return $text;
    }

    /**
     * @param string $introText
     */
    public function setIntroText($introText)
    {
        $this->introText = (string) $introText;
    }
}
