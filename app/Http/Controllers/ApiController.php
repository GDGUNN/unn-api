<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //
    }

    public function getStudentDetails(Request $request)
    {
        if (!($request->has("username"))) {
            return json_encode(array("status" => "failed",
                "message" => "missing username"));
        }

        if (!($request->has("password"))) {
            return json_encode(array("status" => "failed",
            "message" => "missing password"));
        }

        $username = $request->input("username");
        $password = $request->input("password");
        $result = $this->login($username, $password);

        if ($result === false) {
            $response = array("status" => "failed",
                "message" => "authentication failed: incorrect username or password");
        } else {
            try {
            $response = array("status" => "ok",
                "message" => "");
            $response["student"] = $this->extractDetails($result);
            } catch (\Exception $e | \Error $err) {
                $response = array("status" => "failed",
                "message" => "authentication failed: incorrect username or password");
            }
        }

        return json_encode($response);
    }

    public function login($username, $password)
    {
        $ch = curl_init();
        $cookieFile = "cookie.txt";
        $userAgent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36";
        $siteUrl = "http://unnportal.unn.edu.ng/";
        $loginUrl = "http://unnportal.unn.edu.ng/landing.aspx";
        $profileUrl = "http://unnportal.unn.edu.ng/modules/ProfileDetails/BioData.aspx";

        curl_setopt($ch, CURLOPT_URL, $siteUrl);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $loginPage = curl_exec($ch);

        $state = $this->htmlExtractValue($loginPage, "id=\"__VIEWSTATE\"");
        $validation = $this->htmlExtractValue($loginPage, "id=\"__EVENTVALIDATION\"");
        $postfields = array(
            '__EVENTVALIDATION' => $validation,
            '__EVENTARGUMENT' => "",
            '__EVENTTARGET' => "",
            '__VIEWSTATE' => $state,
            'ct100' => 'on',
            'inputUsername' => $username,
            'inputPassword' => $password,
            'login' => 'Login');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
       curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
        $resultOfLogin = curl_exec($ch);

        if ($resultOfLogin == $loginPage) {
            return false;
        }

        curl_setOpt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $profileUrl);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        $profilePage = curl_exec($ch);
        curl_close($ch);


        return $profilePage;
    }

    function htmlExtractValue($htmlString, $value)
    {
        $str = stristr($htmlString, "$value value=\"");
        $vals = explode('value="', $str);
        return stristr($vals[1], '"', true);
    }

    function extractDetails($profile)
    {
        $data = [];
        $dom = new \domDocument;

        $internalErrors = libxml_use_internal_errors(true);

        $dom->loadHTML($profile);
        $dom->preserveWhiteSpace = false;

        libxml_use_internal_errors($internalErrors);

        $sex = $dom->getElementById("ctl00_ContentPlaceHolder1_ddlSex");
        $sexValue = $this->domExtractSelectedValue($sex->getElementsByTagName('option'));

        $dept = $dom->getElementById("ctl00_ContentPlaceHolder1_ddlDepartment");
        $deptValue = $this->domExtractSelectedValue($dept->getElementsByTagName('option'));

        $entryYear = $dom->getElementById("ctl00_ContentPlaceHolder1_ddlEntryYear");
        $entryYearValue = $this->domExtractSelectedValue($entryYear->getElementsByTagName('option'));

        $gradYear = $dom->getElementById("ctl00_ContentPlaceHolder1_ddlGradYear");
        $gradYearValue = $this->domExtractSelectedValue($gradYear->getElementsByTagName('option'));

        $level = $dom->getElementById("ctl00_ContentPlaceHolder1_ddlYearOfStudy");
        $levelValue = $this->domExtractSelectedValue($level->getElementsByTagName('option'));

        $data['surname'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtSurname\" type=\"text\"");
        $data['first_name'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtFirstname\" type=\"text\"");
        $data['middle_name'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtMiddlename\" type=\"text\"");
        $data['sex'] = $sexValue;
        $data['mobile'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$wmeMobileno\" type=\"text\"");
        $data['email'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtEmail\" type=\"text\"");
        $data['department'] = $deptValue;
        $data['level'] = $levelValue;
        $data['entry_year'] = $entryYearValue;
        $data['grad_year'] = $gradYearValue;
        $data['matric_no'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtMatricNo\" type=\"text\"");
        $data['jamb_no'] = $this->htmlExtractValue($profile, "<input name=\"ctl00\$ContentPlaceHolder1\$txtJAMBNo\" type=\"text\"");

        return $data;
    }

    function domExtractSelectedValue($nodeList)
    {
        for ($i = 0; $i < $nodeList->length; $i++) {
            if ($nodeList->item($i)->hasAttribute('selected')
                && $nodeList->item($i)->getAttribute('selected') === "selected"
            ) {
                return $nodeList->item($i)->nodeValue;
            }
        }
    }

}
