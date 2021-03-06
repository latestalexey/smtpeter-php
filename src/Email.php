<?php namespace SMTPeter;
/**
 *  @author Paweł Kuźnik <pawel.kuznik@copernica.com>
 */
class Email
{
    /**
     *  Constants for ::setReturn() method.
     */
    const RETURN_FULL = 'FULL';
    const RETURN_HEADERS = 'HDRS';

    /**
     *  Constants for ::setNotifications() method.
     */
    const NOTIFY_NEVER = 'NEVER';
    const NOTIFY_FAILURE = 'FAILURE';
    const NOTIFY_SUCCESS = 'SUCCESS';
    const NOTIFY_DELAY = 'DELAY';

    /**
     *  The REST API token
     *
     *  @var    string
     */
    private $apiToken;

    /**
     *  The data object that will be send to SMTPeter REST API
     *
     *  @var    stdClass
     */
    private $data;

    /**
     *  Constructor
     *
     *  @param  string  The REST API token that can be obtained from SMTPeter
     *                  website.
     */
    public function __construct($apiToken)
    {
        // initialize object
        $this->apiToken = $apiToken;
        $this->data = new \stdClass;
    }

    /**
     *  Set mail envelope.
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setEnvelope($envelope)
    {
        // set the envelope
        $this->data->envelope = $envelope;

        // allow chaining
        return $this;
    }

    /**
     *  Set mail subject
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setSubject($subject)
    {
        // set subject
        $this->data->subject = $subject;

        // allow chaining
        return $this;
    }

    /**
     *  Set mail text version.
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setText($text)
    {
        // set txt version
        $this->data->text = $text;

        // allow chaining
        return $this;
    }

    /**
     *  Set mail html version.
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setHtml($html)
    {
        // set html version
        $this->data->html = $html;

        // allow chaining
        return $this;
    }

    /**
     *  Set FROM address
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setFrom($from)
    {
        // set from address
        $this->data->from = $from;

        // allow chaining
        return $this;
    }

    /**
     *  Set array of email addresses as recipients.
     *
     *  @param  array   Array of email addresses.
     *  @return SMTPeter\Email
     */
    public function setRecipients(array $addresses)
    {
        // set addresses as recipients
        $this->data->recipients = $addresses;

        // allow chaining
        return $this;
    }

    /**
     *  Append email addresses to recipients list
     *
     *  @param  array   Array of email addresses.
     *  @return SMTPeter\Email
     */
    public function appendRecipients(array $addresses)
    {
        // ensure that we have an array
        if (!property_exists($this->data, 'recipients') || !is_array($this->data->recipients)) $this->data->recipients = array();

        // merge current array with new array
        $this->data->recipients = array_merge($this->data->recipients, $addresses);

        // allow chaining
        return $this;
    }

    /**
     *  Set TO addresses
     *
     *  @param  array
     *  @return SMTPeter\Email
     */
    public function setTo(array $addresses)
    {
        // set TO addresses
        $this->data->to = $addresses;

        // allow chaining
        return $this;
    }

    /**
     *  Set CC addresses
     *
     *  @param  array
     *  @return SMTPeter\Email
     */
    public function setCC(array $addresses)
    {
        // set the addresses
        $this->data->cc = $addresses;

        // allow chaining
        return $this;
    }

    /**
     *  Set original recipent.
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setOriginalRecipient($recipient)
    {
        // ensure that we have dsn property
        if (!property_exists($this->data, 'dsn')) $this->data->dsn = new \stdClass;

        // set original recipient
        $this->data->dsn->orcpt = $recipient;

        // allow chaining
        return $this;
    }

    /**
     *  Set notifications types. In side array should be values from NOTIFY_*
     *  constants. self::NOTIFY_NEVER is special one. It will negate any other
     *  type.
     *
     *  @param  array types of notifications
     *  @return SMTPeter\Email
     */
    public function setNotifications(array $types)
    {
        // ensure that we have dsn property
        if (!property_exists($this->data, 'dsn')) $this->data->dsn = new \stdClass;

        // if we have a never value it should be supplied as it is, not in array
        if (in_array('NEVER', $types)) $this->notify->dsn = self::NOTIFY_NEVER;

        // implode notification types into one comma separated string
        else $this->notify->dsn = implode(',', $types);

        // allow chaining
        return $this;
    }

    /**
     *  Set how much data should be returned. As for varialbe one of RETURN_*
     *  constants should be used.
     *
     *  @param  string
     *  @return SMTPeter\Email
     */
    public function setReturn($return)
    {
        // ensure that we have dsn property
        if (!property_exists($this->data, 'dsn')) $this->data->dsn = new \stdClass;

        // set the return type
        $this->data->dsn->ret = $return;

        // allow chaining
        return $this;
    }

    /**
     *  Set inline css option.
     *
     *  @param  boolean
     *  @return SMTPeter\Email
     */
    public function setInlineCss($inline)
    {
        // set inline css option
        $this->data->inlinecss = $inline;

        // allow chaining
        return $this;
    }

    /**
     *  Set track clicks option.
     *
     *  @param  boolean
     *  @return SMTPeter\Email
     */
    public function setTrackClicks($track)
    {
        // set track clicks option
        $this->data->trackclicks = $track;

        // allow chaining
        return $this;
    }

    /**
     *  Set track bounces option.
     *
     *  @param  boolean
     *  @return SMTPeter\Email
     */
    public function setTrackBounces($track)
    {
        // set track bounces option
        $this->data->trackbounces = $track;

        // allow chaining
        return $this;
    }

    /**
     *  Set track opens option.
     *
     *  @param  boolean
     *  @return SMTPeter\Email
     */
    public function setTrackOpens($track)
    {
        // set track opens option
        $this->data->trackopens = $track;

        // allow chaining
        return $this;
    }

    /**
     *  Method to get the JSON string that would be passed to the server.
     *
     *  @return string
     */
    public function getJSON()
    {
        return json_encode($this->data);
    }

    /**
     *  Send the email.
     *
     *  @return mixed   If sending is successful and API acknowledge the request
     *                  this method will return TRUE. If there were any problem
     *                  with sending ot API does not acknowledge the response
     *                  it will return a stdClass with explanation what went wrong.
     *                  The error message will look like this:
     *                  {
     *                      "error": "The actual error message"
     *                  }
     */
    public function send()
    {
        // initialize curl instance
        $handle = curl_init();

        // encode data into string
        $dataString = json_encode($this->data);

        // set needed curl options
        curl_setopt($handle, CURLOPT_URL, "https://www.smtpeter.com/v1/send?access_token={$this->apiToken}");
        curl_setopt($handle, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json',
            'Content-Length: '.strlen($dataString)
        ));
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $dataString);

        // send request to API
        $result = curl_exec($handle);

        // get http code
        $httpcode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // close curl instance
        curl_close($handle);

        // if we have a 200 response we can say that sending was successful
        if ($httpcode == 200) return true;

        // return result
        return json_decode($result);
    }
}
