<?

class HomeKitCodes
{

    private $instanceID = 0;
    private $sendDebug = null;
    private $getBuffer = null;
    private $setBuffer = null;

    private function SendDebug($message) {
        call_user_func($this->sendDebug, "HomeKitCodes", $message, 0);
    }

    public function __construct($instanceID, $sendDebug, $getBuffer, $setBuffer) {

        $this->instanceID = $instanceID;
        $this->sendDebug = $sendDebug;
        $this->getBuffer = $getBuffer;
        $this->setBuffer = $setBuffer;

    }

    private function isValidSetupCode($setupCode) {

        return !in_array($setupCode, Array(
            "000-00-000",
            "111-11-111",
            "222-22-222",
            "333-33-333",
            "444-44-444",
            "555-55-555",
            "666-66-666",
            "777-77-777",
            "888-88-888",
            "999-99-999",
            "123-45-678",
            "876-54-321"
        ));

    }

    public function generateSetupCode() {

        $code = "000-00-000";

        while(!$this->isValidSetupCode($code)) {
            $number = sprintf("%08d", random_int (0, 99999999));
            $code = substr($number, 0, 3) . "-" . substr($number, 3, 2) . "-" . substr($number, 5, 3);
        }

        //The code expires after 5 minutes or if a new one is generated
        $setupCode = Array(
            "expires" => time() + 5 * 60,
            "code" => $code
        );

        call_user_func($this->setBuffer, "SetupCode", json_encode($setupCode));

        return $code;

    }

    public function getSetupCode() {

        $setupCode = call_user_func($this->getBuffer, "SetupCode");

        if($setupCode == "")
            return null;

        $setupCode = json_decode($setupCode, true);

        if(time() > $setupCode["expires"])
            return null;

        $this->SendDebug("Getting current setup code: " . $setupCode["code"]);

        return $setupCode['code'];

    }

    public function removeSetupCode() {

        $code = $this->getSetupCode();

        if($code == null)
            return;

        call_user_func($this->setBuffer, "SetupCode", "");

        $this->SendDebug("Removing current setup code: " . $code);

    }

}