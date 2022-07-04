<?php

namespace Api\Models;


use Api\Models\DB as Connection;
use PDO;

class User
{

    private $connObj = null;

    public function __construct()
    {
        $this->connObj = new Connection();
    }

    /* ******************************** DUPLICATE MOBILE NUMBER ******************************** */

    public function getDupMobile($datas = null)
    {
        $getjsonData = json_decode($datas);
        $mobileno = $getjsonData->mobile_no;
        $hmobileno = $getjsonData->hmobile_no;

        $sql = "SELECT * FROM bio_data WHERE mobile = :mobileno AND mobile != :hmobileno";
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->bindparam(":mobileno", $mobileno);
        $stmt->bindparam(":hmobileno", $hmobileno);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $retData = array("Record" => $numRows);
        return $retData;
    }

    /* ******************************** DUPLICATE MOBILE NUMBER ******************************** */

    /* ******************************** DUPLICATE EMAIL ID ******************************** */

    public function getDupEmail($datas = null)
    {
        $getjsonData = json_decode($datas);
        $email = $getjsonData->email_id;
        $hemail = $getjsonData->hemail_id;

        $sql = "SELECT * FROM bio_data WHERE email = :email AND email != :hemail";
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->bindparam(":email", $email);
        $stmt->bindparam(":hemail", $hemail);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $retData = array("Record" => $numRows);
        return $retData;
    }

    /* ******************************** DUPLICATE EMAIL ID ******************************** */

    /* ******************************** INSERT BIO-DATA ******************************** */

    public function createUser($datas = null)
    {
        $insertSQL = "";

        $getjsonData = json_decode($datas);
        $id = $getjsonData->UserID;
        $regName = $getjsonData->RegName;
        $courseName = $getjsonData->CourseName;
        $gender = $getjsonData->Gender;
        $email = $getjsonData->Email;
        $mobile = $getjsonData->Mobile;
        $website = $getjsonData->Website;
        $about = $getjsonData->About;
        $languages = $getjsonData->Languages;
        $profileImage = $getjsonData->ProfileImage;
        $profileImageType = $getjsonData->ProfileImageType;
        $profileIDCard = $getjsonData->ProfileID;
        $certificateImage = $getjsonData->CertificateImage;
        $certificateImageType = $getjsonData->CertificateImageType;

        $insertSQL = "INSERT INTO bio_data VALUES(:userid,:full_name,:course_name,:email,:mobile,:website,:gender,
                      :languages,:about,:profile_pic,:profile_pic_type,:certificate_image,:certificate_image_type,
                      :id_card)";
        $stmt = $this->connObj->getConnection()->prepare($insertSQL);
        $stmt->bindParam(':userid', $id);
        $stmt->bindParam(':full_name', $regName);
        $stmt->bindParam(':course_name', $courseName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':website', $website);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':languages', $languages);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':profile_pic', $profileImage);
        $stmt->bindParam(':profile_pic_type', $profileImageType);
        $stmt->bindParam(':certificate_image', $certificateImage);
        $stmt->bindParam(':certificate_image_type', $certificateImageType);
        $stmt->bindParam(':id_card', $profileIDCard);

        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /* ******************************** INSERT BIO-DATA ******************************** */

    /* ******************************** SELECTED BIO-DATA DETAILS ******************************** */

    public function getSelectedUser($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $user_id = $getjsonData->UserID;

        $sql = "SELECT * FROM bio_data WHERE user_id=:userid";
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->bindParam(':userid', $user_id);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $retData = array("Record" => $numRows, "Data" => $data);
        return $retData;
    }

    /* ******************************** SELECTED BIO-DATA DETAILS ******************************** */

    /*     * ******************************* BIO-DATA DETAILS *********************************/

    public function getAllUsers($datas = null)
    {
        $limit = "";
        if($datas != null){
            $getjsonData = json_decode($datas);
            $startIndex = $getjsonData->StartIndex;
            $records = $getjsonData->RecordsToBeShown;
    
            if (($startIndex != "") && ($records != "")) {
                $limit = "LIMIT $startIndex, $records";
            } else {
                $limit = "";
            }
        }
     
        $sql = "SELECT * FROM bio_data ORDER BY full_name " . $limit;
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $retData = array("Record" => $numRows, "Data" => $data);
        return $retData;
    }

    /*     * ******************************* BIO-DATA DETAILS ******************************** */

    /*     * ******************************* BIO-DATA UPDATE ******************************** */

    public function updateUser($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $id = $getjsonData->UserID;
        $regName = $getjsonData->RegName;
        $courseName = $getjsonData->CourseName;
        $gender = $getjsonData->Gender;
        $email = $getjsonData->Email;
        $mobile = $getjsonData->Mobile;
        $website = $getjsonData->Website;
        $about = $getjsonData->About;
        $languages = $getjsonData->Languages;
        $profileImage = $getjsonData->ProfileImage;
        $profileImageType = $getjsonData->ProfileImageType;
        $profileIDCard = $getjsonData->ProfileID;
        $certificateImage = $getjsonData->CertificateImage;
        $certificateImageType = $getjsonData->CertificateImageType;

        $sql .= "UPDATE bio_data SET full_name=:full_name, course_name=:course_name, gender=:gender, ";
        $sql .= "languages=:languages, mobile=:mobile, email=:email, website=:website, about=:about, ";
        $sql .= "profile_pic=:profile_pic, profile_pic_type=:profile_pic_type, id_card=:id_card, ";
        $sql .= "certificate_image=:certificate_image, certificate_image_type=:certificate_image_type WHERE user_id=:userid";
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->bindParam(':full_name', $regName);
        $stmt->bindParam(':course_name', $courseName);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':languages', $languages);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':website', $website);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':profile_pic', $profileImage);
        $stmt->bindParam(':profile_pic_type', $profileImageType);
        $stmt->bindParam(':certificate_image', $certificateImage);
        $stmt->bindParam(':certificate_image_type', $certificateImageType);
        $stmt->bindParam(':id_card', $profileIDCard);
        $stmt->bindParam(':userid', $id);

        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* BIO-DATA UPDATE ******************************** */

    /*     * ******************************* DELETE BIO-DATA ******************************** */

    public function deleteUser($datas = null)
    {
        $sql = "";
        $getjsonData = json_decode($datas);
        $userID = $getjsonData->UserID;
        $sql = "DELETE FROM bio_data WHERE user_id=:user_id";
        $stmt = $this->connObj->getConnection()->prepare($sql);
        $stmt->bindParam(':user_id', $userID);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* DELETE BIO-DATA ******************************** */
}