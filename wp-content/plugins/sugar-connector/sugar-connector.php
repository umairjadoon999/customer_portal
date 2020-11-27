<?php

/*

Plugin Name: Sugar Connector

Plugin URI: http://www.outrightcrm.com/

Description: Plugin used to configure the API script which communicate with CRM

Version: 1.0

Author: Dileep Awasthi

Author URI: http://www.outrightcrm.com/

License: GPL2

*/

/*  This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

//ob_start();

class sugarConnector
{

	var $crm_url;

	var $crm_uname;

	var $crm_pass;

	var $apiURL;

	var $default_phone;



	function __construct()
	{
		$this->crm_url = get_option('crm_url');
		$this->crm_uname = get_option('crm_uname');
		$this->crm_pass = get_option('crm_pass');
		$this->default_phone = get_option('default_phone');
		$this->api_url = $this->crm_url . '/service/v4/rest.php';
		add_action('init', array($this, 'myStartSession'), 1);
		add_action('wp_head', array($this, 'mycss'));
		add_action('wp_ajax_savesetting', array($this, 'save_setting'));
		add_action('wp_ajax_portalLogin', array($this, 'portalLoginSave'));
		//add_action('wp_ajax_portalLogin', array($this, 'login_screen_method'));
		//add_action('wp_ajax_nopriv_portalLogin', array($this, 'login_screen_method'));
		add_action('wp_ajax_approved', array($this, 'approvedSave')); //Added by Usman on 22Aug2019
		add_action('wp_ajax_nopriv_approved', array($this, 'approvedSave')); //Added by Usman on 22Aug2019
		add_action('wp_ajax_nopriv_portalLogin', array($this, 'portalLoginSave'));
		//Added by Rao on 25Sep2019 for questionaire
		add_action('wp_ajax_updateuser', array($this, 'updateUserInfo')); //Added by Usman on 22Aug2019
		add_action('wp_ajax_nopriv_updateuser', array($this, 'updateUserInfo')); //Added by Usman on 22Aug2019
		add_action('wp_ajax_updateQuestionnaire', array($this, 'updateQuestionnaireInfo'));
		add_action('wp_ajax_nopriv_updateQuestionnaire', array($this, 'updateQuestionnaireInfo'));
		//---------------------------------
		add_action('wp_ajax_portalLogout', array($this, 'portalLogout'));
		add_action('wp_ajax_nopriv_portalLogout', array($this, 'portalLogout'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_shortcode('login_screen', array($this, 'login_screen_method'));
		add_shortcode('loanApplicationForm', array($this, 'loanApplicationForm_func'));
		add_shortcode('crmDocuploadFrom', array($this, 'docUploadForm_func'));
		add_shortcode('crmAllForms', array($this, 'docAllForms_func'));
		add_shortcode('crmDocumentList', array($this, 'crmDocumentList_func'));

		//Added by Rao on 3oct2019 for script notes
		add_action('wp_ajax_updateScript', array($this, 'updateScriptNotes'));
		add_action('wp_ajax_nopriv_updateScript', array($this, 'updateScriptNotes'));

		//add_shortcode( 'myProfilePage', array($this,'myProfilePage_func') ); page updated

		//Added by Rao 18/9/2019
		add_shortcode('myProfileInfo', array($this, 'myProfileInfo_func'));
		add_shortcode('calendarInfo', array($this, 'calenderInfo_func'));
		add_shortcode('updateMyInfo', array($this, 'updateMyInfo_func'));
		add_shortcode('questionnairePage', array($this, 'questionnairePage_func'));
		add_shortcode('loanApplicationForm', array($this, 'printQuestionnairePage_func'));
		add_shortcode('casesList', array($this, 'casesListPage_func'));
		add_shortcode('caseDetails', array($this, 'caseDetails_func'));
		add_shortcode('createCase', array($this, 'createCasePage_func'));
		add_shortcode('changePassword', array($this, 'changePassword_func')); //added by umair khan on 02-11-2020 for Cases List
		//----------------------------
		//Added By Rao 1/10/2019
		add_shortcode('customerScript', array($this, 'customerScript_func'));
		//-----------
		add_action('wp_ajax_updatepassword', array($this, 'updatePasswordInfo')); //Added by Umair on 9-11-2020
		add_action('wp_ajax_nopriv_updatepassword', array($this, 'updatePasswordInfo')); //Added by Umair on 9-11-2020
		add_action('wp_ajax_caseUpdates', array($this, 'updateCaseNotes')); //Added by Umair on 10-11-2020
		add_action('wp_ajax_nopriv_caseUpdates', array($this, 'updateCaseNotes')); //Added by Umair on 10-11-2020
		add_action('wp_ajax_caseCreate', array($this, 'createCase_fun')); //Added by Umair on 10-11-2020
		add_action('wp_ajax_nopriv_caseCreate', array($this, 'createCase_fun')); //Added by Umair on 10-11-2020
		add_action('wp_ajax_updoc', array($this, 'doUploadDocument'));
		add_action('wp_ajax_nopriv_updoc', array($this, 'doUploadDocument'));
		add_action('wp_ajax_leadDetailsUpdate', array($this, 'leadDetailsUpdate'));
		add_action('wp_ajax_nopriv_leadDetailsUpdate', array($this, 'leadDetailsUpdate'));
		//require_once( plugin_dir_path( __FILE__ ) . 'docs.php' );
	}

	function myStartSession()
	{
		if (!session_id()) {
			session_start();
		}
	}



	function mycss()
	{
		echo '<style> 
					#contentInfo { padding-top: 50px;}
					#headingId { font-size:30px;color:#1E76BA;font-weight:bold;}
					#btnLink { padding-top: 150px; }
					#topbuttons { margin-bottom: 12px; }
					#topbuttons a { font-size:16px;background: #58abb7;color: #FFF; padding: 4px 9px; border-radius: 3px; margin-right: 11px; }
				 </style>';
	}

	// li code changed by hassan view calendar on 8/8/2019
	function leftnav()
	{

		$data = '<div id="leftnav" style="font-size:12px !important;">
					<ul>
					<li><a href="' . get_permalink(get_page_by_title("My Profile Info")) . '" >My Profile</a></li>
					<li><a href="' . get_permalink(get_page_by_title("Update My Info")) . '">Update my info</a></li>
					<li><a href="' . get_permalink(get_page_by_title("Cases")) . '">Cases</a></li><!-- Added By umair Khan on 02-11-2020-->
					<li><a href="' . get_permalink(get_page_by_title("Create Case")) . '">Create Case</a></li><!-- Added By umair Khan on 13-11-2020-->
					<li><a href="' . get_permalink(get_page_by_title("Change Password")) . '">Change Password</a></li>
					<li><a href="' . home_url() . '/wp-admin/admin-ajax.php?action=portalLogout">Logout</a></li>
					</ul>
				</div>';
		return $data;
	}

	function call($method, $parameters)
	{
		ob_start();
		$curl_request = curl_init();
		curl_setopt($curl_request, CURLOPT_URL, $this->api_url);
		curl_setopt($curl_request, CURLOPT_POST, 1);
		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl_request, CURLOPT_HEADER, 1);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
		$jsonEncodedData = json_encode($parameters);
		$post = array(
			"method" => $method,
			"input_type" => "JSON",
			"response_type" => "JSON",
			"rest_data" => $jsonEncodedData
		);

		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($curl_request);
		curl_close($curl_request);
		$result = explode("\r\n\r\n", $result, 2);
		$response = json_decode($result[1]);
		ob_end_flush();
		return $response;
	}

	//************** API LOGIN ********************'

	public function doAPILogin()
	{
		$returnArr = array();
		$login_parameters = array(
			"user_auth" => array(
				"user_name" => $this->crm_uname,
				"password" => md5($this->crm_pass),
				"version" => "1"
			),
			"application_name" => "RestTest",
			"name_value_list" => array(),
		);
		$login_result = $this->call("login", $login_parameters, $this->api_url);
		return $login_result->id;
	}

	function portalLogout()
	{
		unset($_SESSION['portal']);
		wp_redirect(home_url());
		exit();
	}

	function portalLoginSave()
	{
		$loginArr = $this->doAPILogin();
		$portalUser = $_POST['p_usern'];
		$portalpass = $_POST['p_pass'];
		$portalLoginParameters = array(
			"session" => $loginArr,
			"user_auth" => array(
				"user_name" => $portalUser,
				"password" => $portalpass,
				"version" => "1"
			),
			"application_name" => "PortalUserLogin",
			"name_value_list" => array(),
		);
		$portalUserLogin = $this->call("portal_user_login", $portalLoginParameters);
		if (isset($portalUserLogin->contactID)) {

			$profilePage['firstName'] = $portalUserLogin->FirstName;
			$profilePage['lastName'] = $portalUserLogin->LastName;
			$profilePage['assigned_user_id'] = $portalUserLogin->AssignedUserID;
			$profilePage['email'] = $portalUserLogin->EmailAddress;
			$userInfoParameters = array(
				"session" => $loginArr,
				"user_contact" => array(
					"usersID" => $portalUserLogin->AssignedUserID,
					"version" => "1"
				),
				"application_name" => "Users Details",
			);

			$repoDetails = $this->call("crm_profile_details", $userInfoParameters);
			$profilePage['repoName'] = $repoDetails[0]->repoName;
			$profilePage['phone_work'] = $repoDetails[0]->phone_work != '' ? $repoDetails[0]->phone_work : $this->default_phone;
			$profilePage['email_address'] = $repoDetails[0]->email_address != '' ? $repoDetails[0]->email_address : 'Not Available';
			$_SESSION['portal'] = array(
				'name' => trim($profilePage['firstName'] . ' ' . $profilePage['lastName']),
				'email' => $portalUserLogin->EmailAddress,

				'phone_work' => $profilePage['phone_work'],
				'repoName' => trim($profilePage['repoName']),
				'assigned_user_id' => $portalUserLogin->AssignedUserID,
				'email_address' => $profilePage['email_address'],
				'userContactID' => $portalUserLogin->contactID,
				'account_id' => $portalUserLogin->account_id,
			);
			wp_redirect(get_permalink(get_page_by_title("My Profile Info")));
			exit();
		}

		wp_redirect(home_url() . '?login=1');
	}



	function login_screen_method()
	{
		if ($_SESSION['portal']['name']) {
			$loginArr = $this->doAPILogin();
			$crmDocumentListsParameters = array(
				"session" => $loginArr,
				"user_contact" => array(
					"contactID" => $_SESSION["portal"]["userContactID"],
					"version" => "1"
				),
				"application_name" => "Portal-Document",
				"name_value_list" => array(),
			);
			$documentLists = $this->call("organization_profile_details", $crmDocumentListsParameters);
			$profilePage['firstname'] = $documentLists->firstname;
			$profilePage['lastname'] = $documentLists->lastname;
			$profilePage['phone'] = $documentLists->phone;
			$profilePage['EmailAddress'] = $documentLists->emailAddress;
			$profilePage['repoName'] = $documentLists->repoName;
			$profilePage['repoEmail'] = $documentLists->RepEmailAddress;
			$profilePage['repoContact'] = $documentLists->repoPhone;
			echo $this->leftnav();
			echo '<div>';
			echo '
			 <div id="profileDiv"><div>
		  
			 <div id="loanDetails">
			  <div id=""> 
			  <table style="width:100%">
			  <tr>
				<th colspan="2" style="background-color: rgb(270, 140, 0); color: white; border-top-left-radius: 10px; border-top:none;border-left:none"><span>My Profile Details</span></th>
				<th colspan="2" style="background-color: rgb(270, 140, 0);color: white; border-top-right-radius: 10px; border-top:none;border-right:none"><span id="">MINT Representative</span></th>
			  </tr>
			  <tr>
				<td colspan="1">Contact Name:</td>
				<td><span> <strong> ' . $profilePage['firstname'] . ' ' . $profilePage['lastname'] . ' </strong></span></td>
				<td colspan="1">Name:</td>
				<td><strong> ' . $profilePage['repoName'] . ' </strong></td>
			  </tr>
			  <tr>
				<td>Email:</td>
				<td><span> <strong> ' . $profilePage['EmailAddress'] . ' </strong></span></td>
				<td>Email:</td>
				<td><span><strong> ' . $profilePage['repoEmail'] . ' </strong></span></td>
			  </tr>
			  <tr>
				<td>Phone:</td>
				<td><span> <strong> ' . $profilePage['phone'] . '</strong></span></td>
				<td>Contact No:</td>
				<td><strong> ' . $profilePage['repoContact'] . ' </strong></td>
			  </tr>
			  <tr>
				<!--<td>Project Status:</td>
				<td><span> <strong> ' . $profilePage['project_status'] . ' </strong></span></td>
				<td></td>
				<td></td>-->
			  </tr>				
				</table>
			  </div>
		  </div>
		  </div>';
		} else {
			$login = $_REQUEST['login'];
			if ($login == 1) {
				echo '<div id="alert" class="alert alert1">
				<span class="closebtn" onclick="displayNone();">&times;</span>
				Invalid Username or Password
			  </div>';
			} else {
				$login = 0;
			}

			echo '<form action="' . home_url() . '/wp-admin/admin-ajax.php" name="login_form" method="post" style="width:50%;">
				  <input type="hidden" name="action" value="portalLogin" >
			  	<div>
				  <p>UserName: <input id="u_namee" name="p_usern" type="text" onkeyup="saveValue(this);" required  /></p>
				  <p>Password: <input id="u_pass" name="p_pass" type="password" onkeyup="saveValue(this);" required /></p>
				  <input type="submit" name="loginportal" value="login" id="update" >
				  </div>
				  </form>
				  ';
		}
	}

	function admin_menu()
	{
		add_options_page(
			'CRM Connector',
			'CRM Connector',
			'manage_options',
			'options_page_slug',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function save_setting()
	{
		update_option('crm_url', $_POST['crm_url']);
		update_option('crm_uname', $_POST['crm_uname']);
		update_option('crm_pass', $_POST['crm_pass']);
		update_option('default_phone', $_POST['default_phone']);
		wp_redirect('options-general.php?page=options_page_slug');
		exit();
	}

	function  settings_page()
	{
		echo '<h2>Welcome To CRM API Plugin Administration Page</h2>
		<br />
		<br />
		<form id="crm-api" method="post" action="' . home_url() . '/wp-admin/admin-ajax.php"> 
					<h3>Setting for CRM API Configuration</h3>  
					CRM URL : <input type="textbox" name="crm_url" value="' . $this->crm_url . '">
					<br /><br />
					API USER : <input type="textbox" name="crm_uname" value="' . $this->crm_uname . '">
					<br /><br />
					API PASSWORD : <input type="textbox" name="crm_pass" value="' . $this->crm_pass . '">
					<br /><br />
					Default Phone : <input type="textbox" name="default_phone" value="' . $this->default_phone . '">
					<br /><br />
					<p><input type="hidden" name="action" value="savesetting">
					  <input type="submit" value="Save" class="button button-primary" name="submit" />
					</p>
				</form>';
	}

	function myProfileInfo_func()
	{
		$passwordUpdate = $_REQUEST['password'];
		if ($passwordUpdate == 1) {
			echo '<div id="alert" class="alert">
		   <span class="closebtn" onclick="displayNone();">&times;</span>
		   Password Updated
		 </div>';
		} else {
		}
		$profileInfoUpdate = $_REQUEST['infoUpdate'];
		if ($profileInfoUpdate == 1) {
			echo '<div id="alert" class="alert">
		<span class="closebtn" onclick="displayNone();">&times;</span>
		Profile Information Updated
	  </div>';
		} else {
		}
		if (!isset($_SESSION['portal'])) {
			get_header();
			wp_redirect(home_url());
			exit();
		}
		$loginArr = $this->doAPILogin();
		$crmDocumentListsParameters = array(
			"session" => $loginArr,
			"user_contact" => array(
				"contactID" => $_SESSION["portal"]["userContactID"],
				"version" => "1"
			),
			"application_name" => "Portal-Document",
			"name_value_list" => array(),
		);
		$documentLists = $this->call("organization_profile_details", $crmDocumentListsParameters);
		$profilePage['firstname'] = $documentLists->firstname;
		$profilePage['lastname'] = $documentLists->lastname;
		$profilePage['phone'] = $documentLists->phone;
		$profilePage['project_status'] = $documentLists->project_status;
		$profilePage['EmailAddress'] = $documentLists->emailAddress;
		$profilePage['status'] = $documentLists->status;
		$profilePage['repoName'] = $documentLists->repoName;
		$profilePage['repoEmail'] = $documentLists->RepEmailAddress;
		$profilePage['repoContact'] = $documentLists->repoPhone;
		echo '<script>
             jQuery(document).ready(function(){
                 jQuery("div.page-title-title").html("<h1>' . $profilePage['account_name'] . '</h1>");
             });
             </script>';
		$crmDoc .= '
				<div id="profileDiv"><div style="">
				
				   <div id="loanDetails">
					<div id=""> 
					<table style="width:100%">
					<tr>
					  <th colspan="2" style="background-color: rgb(270, 140, 0); color: white; border-top-left-radius: 10px; border-top:none;border-left:none"><span>My Profile Details</span></th>
					  <th colspan="2" style="background-color: rgb(270, 140, 0);color: white; border-top-right-radius: 10px; border-top:none;border-right:none"><span id="">MINT Representative</span></th>
					</tr>
					<tr>
					  <td colspan="1">Contact Name:</td>
					  <td><span> <strong> ' . $profilePage['firstname'] . ' ' . $profilePage['lastname'] . ' </strong></span></td>
					  <td colspan="1">Name:</td>
					  <td><strong> ' . $profilePage['repoName'] . ' </strong></td>
					</tr>
					<tr>
					  <td>Email:</td>
					  <td><span> <strong> ' . $profilePage['EmailAddress'] . ' </strong></span></td>
					  <td>Email:</td>
					  <td><span><strong> ' . $profilePage['repoEmail'] . ' </strong></span></td>
					</tr>
					<tr>
					  <td>Phone:</td>
					  <td><span> <strong> ' . $profilePage['phone'] . '</strong></span></td>
					  <td>Contact No:</td>
					  <td><strong> ' . $profilePage['repoContact'] . ' </strong></td>
					</tr>
					<tr>
					<!--  <td>Project Status:</td>
					  <td><span> <strong> ' . $profilePage['project_status'] . ' </strong></span></td>
					  <td></td>
					  <td></td> -->
					</tr>				
					  </table>
					</div>
				</div>
				</div>
				<div id="Owner_Information" style="float:right;width:48%;">
				';
		echo $this->leftnav();
		echo $crmDoc . "</div>";
	}
//Start Updating The User Info
	function updateMyInfo_func()
	{
		if (!isset($_SESSION['portal'])) {
			wp_redirect(home_url());
		}
		$loginArr = $this->doAPILogin();
		$crmDocumentListsParameters = array(
			"session" => $loginArr,
			"user_contact" => array(
				"contactID" => $_SESSION["portal"]["userContactID"],
				"version" => "1"
			),
			"application_name" => "Portal-Document",
			"name_value_list" => array(),
		);
		$InfoLists = $this->call("updateMyInfo_details", $crmDocumentListsParameters);
		$updateInfo['sponser'] = $InfoLists->sponser;
		$updateInfo['firstname'] = $InfoLists->firstname;
		$updateInfo['lastname'] = $InfoLists->lastname;
		$updateInfo['phone'] = $InfoLists->phone;
		$updateInfo['EmailAddress'] = $InfoLists->EmailAddress;
		$crmDoc .= '
					<div id="profileDiv"><div style="">
					   <div id="loanDetails">
						<div id=""> 
						<form action="' . home_url() . '/wp-admin/admin-ajax.php" method="post" id="updateForm">
							<input type="hidden" name="action" value="updateuser">
							<table style="width:100%;border: none !important;">
								
								<tr><td style="border: none"></td></tr>
								<!--<tr>
									<td style="border: none; width: 25%;"><label>Sponser: </label></td>
									<td style="border: none"><input name="account_name" type="text" value="' . $updateInfo['sponser'] . '"  style="width: 100%;"></td>
								</tr> -->
								<tr>
									<td style="border: none"><label>First Name: </label></td>
									<td style="border: none"><input name="first_name" type="text" value="' . $updateInfo['firstname'] . '" style="width: 100%;"></td>
								</tr> 
								<tr>
									<td style="border: none"><label>Last Name: </label></td>
									<td style="border: none"><input name="last_name" type="text" value="' . $updateInfo['lastname'] . '" style="width: 100%;"></td>
								</tr> 
								<tr>
									<td style="border: none"><label>Office Phone: </label></td>
									<td style="border: none"><input name="phone_mobile" type="text" value="' . $updateInfo['phone'] . '" style="width: 100%;"></td>
								</tr> 
								<!--<tr>
									<td style="border: none"><label >Email Address: </label></td>
									<td style="border: none"><input name="email1" type="email" value="' . $updateInfo['EmailAddress'] . '" style="width: 100%;"></td>
								</tr> -->
								<tr><td style="border: none"></td></tr>
							</table>	
							<input type="submit" value="Update" id="update">
						</form>
						</div>				
					</div>
					</div>
					<div id="Owner_Information" style="float:right;width:48%;">
					';
		echo $this->leftnav();
		echo $crmDoc . "</div>";
	}

	function updateUserInfo()
	{
		$loginArr = $this->doAPILogin();
		unset($_POST['action']);
		$_POST['id'] = $_SESSION['portal']["userContactID"];
		$set_entry_parameters = array(
			"session" => $loginArr,
			"module_name" => "Contacts",
			"name_value_list" =>  $_POST,
		);
		$res = $this->call("set_entry", $set_entry_parameters);
		$_SESSION['portal']["first_name"] = $_POST['first_name'];
		$_SESSION['portal']["last_name"] = $_POST['last_name'];
		$_SESSION['portal']["phone_mobile"] = $_POST['phone_mobile'];
		$_SESSION['portal']["email1"] = $_POST['email1'];
		wp_redirect(get_permalink(get_page_by_title("My Profile Info")) . '?infoUpdate=1');
	}
//End Updating The User Info

//Start added by umair khan on 02-11-2020 for Cases
	function casesListPage_func()
	{
		$caseCreated = $_REQUEST['caseCreated'];
		if ($caseCreated == 1) {
			echo '<div id="alert" class="alert">
		<span class="closebtn" onclick="displayNone();">&times;</span>
		Case Created
	  </div>';
		} else {
		}
		if (!isset($_SESSION['portal'])) {
			wp_redirect(home_url());
		}
		$loginArr = $this->doAPILogin();
		$crmCasesListsParameters = array(
			"session" => $loginArr,
			"user_contact" => array(
				"contactID" => $_SESSION["portal"]["userContactID"],
				"accountID" => $_SESSION["portal"]["account_id"],
				"version" => "1"
			),
			"application_name" => "Cases",
			"name_value_list" => array(),
		);
		$crm_cases_list_detail = $this->call("crm_cases_lists", $crmCasesListsParameters);
		echo $this->leftnav();
		echo '<div id="profileDiv">
		<table style="width:100%; border-spacing:0; margin:0; margin-top:20px;" align="center" id="table_id" class="display">
				<thead>
							<tr>
							<th style="background-color: rgb(270, 140, 0); color: white; border-top-left-radius: 10px">C.NO</th>
							<th style="background-color: rgb(270, 140, 0); color: white;">Case Name</th>
							<th style="background-color: rgb(270, 140, 0); color: white;">Priority</th>
							<th style="background-color: rgb(270, 140, 0); color: white;">Status</th>
							<th style="background-color: rgb(270, 140, 0); color: white;">Assigned To</th>
							<th style="background-color: rgb(270, 140, 0); color: white; border-top-right-radius: 10px">Date Created</th>
							</tr>
				</thead>
			  <tbody>';


		if ($crm_cases_list_detail != "" && $crm_cases_list_detail != "NULL") {
			foreach ($crm_cases_list_detail as $caseList) {
				if ($caseList->priority == "P1") {
					$caseList->priority = "High";
				} else if ($caseList->priority == "P2") {
					$caseList->priority = "Medium";
				} else if ($caseList->priority == "P3") {
					$caseList->priority = "Low";
				}
				if ($caseList->Status == "Open_New") {
					$caseList->Status = "New";
				} else if ($caseList->Status == "Open_Assigned") {
					$caseList->Status = "Assigned";
				} else if ($caseList->Status == "Open_Pending Input") {
					$caseList->Status = "Pending Input";
				}
				$caseID = $caseList->cases_id;
				echo ' <tr>
								<td align="center">' . $caseList->Number . '</td>
								<td align="center"><a href="' . get_permalink(get_page_by_title("Case Details")) . '?caseID=' . $caseID . ' ">' . $caseList->Subject . '</a></td>
								<td>' . $caseList->priority . '</td>
								<td>' . $caseList->Status . '</td>
								<td>' . $caseList->user_name . '</td>
								<td align="center">' . $caseList->date_entered . '</td>
							  </tr>';
			}
		}
		echo "</tbody></table></div>";
	}
//end added by umair khan on 02-11-2020 for Cases

//Start Added For Password Updation
	function changePassword_func()
	{
		if (!isset($_SESSION['portal'])) {
			wp_redirect(home_url());
		}
		$loginArr = $this->doAPILogin();
		$crmCasesListsParameters = array(
			"session" => $loginArr,
			"user_contact" => array(
				"contactID" => $_SESSION["portal"]["userContactID"],
				"version" => "1"
			),
			"application_name" => "Contacts",
			"name_value_list" => array(),
		);
		$portal_password_detail = $this->call("changePassword_func", $crmCasesListsParameters);
		$updatePassword['portalPassword'] = $portal_password_detail->portalPassword;
		$crmDoc .= '
		<div id="profileDiv"><div style="">
		   <div id="loanDetails">
			<div id=""> 
			<form action="' . home_url() . '/wp-admin/admin-ajax.php" method="post" id="updateForm">
				<input type="hidden" name="action" value="updatepassword">
				<table style="width:100%;border: none !important;">
					
					<tr><td style="border: none"></td></tr>
					<tr>
						<td style="border: none; width: 25%;"><label>Password: </label></td>
						<td style="border: none"><input name="portal_password_c" id = "password" type="password" value="' . $updatePassword['portalPassword'] . '"  style="width: 100%;" onchange="check_pass();"></td>
					</tr> 
					<tr>
						<td style="border: none; width: 25%;"><label>Confirm Password: </label></td>
						<td style="border: none"><input name="confirmPassword" type="password" id = "confirm_password" value="' . $updatePassword['portalPassword'] . '"  style="width: 100%;" onchange="check_pass();"></td>
					</tr>
					<tr>
						<td style="border: none; width: 25%;"><label>Please Confirm The Passwords: </label></td>
						<td style="border: none"><input type="checkbox" id="checkbox" required onclick="check_passs();"></td>
					</tr>
				</table>	
				<input type="submit" value="Update" id="update" disabled>
			</form>
			</div>				
		</div>
		</div>
		<div id="Owner_Information" style="float:right;width:48%;">
		';
		echo $this->leftnav();
		echo $crmDoc . "</div>";
	}
	function updatePasswordInfo()
	{
		$loginArr = $this->doAPILogin();
		unset($_POST['action']);
		$_POST['id'] = $_SESSION['portal']["userContactID"];
		$set_entry_parameters = array(
			"session" => $loginArr,
			"module_name" => "Contacts",
			"name_value_list" =>  $_POST,
		);
		$res = $this->call("set_entry", $set_entry_parameters);
		$_SESSION['portal']["portal_password_c"] = $_POST['portal_password_c'];
		wp_redirect(get_permalink(get_page_by_title("My Profile Info")) . '?password=1');
	}
//Start Added For Password Updation


	function caseDetails_func()
	{
		$noteCreated = $_REQUEST['note_created'];
		if ($noteCreated == 1) {
			echo '<div id="alert" class="alert">
		<span class="closebtn" onclick="displayNone();">&times;</span>
		Note / Update Created
	  </div>';
		} else {
		}
		if (isset($_REQUEST['caseID'])) {
			$caseID = $_REQUEST['caseID'];
		} else {
			echo "nothing";
		}

		if (!isset($_SESSION['portal'])) {
			wp_redirect(home_url());
		}

		$loginArr = $this->doAPILogin();

		$crmCasesListsParameters = array(
			"session" => $loginArr,
			"cases" => array(
				"caseID" => $_REQUEST['caseID'],
				"version" => "1"
			),
			"application_name" => "Cases",
			"name_value_list" => array(),
		);
		$response = $this->call("caseDetails", $crmCasesListsParameters);
		echo $this->leftnav();
		if ($response->priority == "P1") {
			$response->priority = "High";
		} else if ($response->priority == "P2") {
			$response->priority = "Medium";
		} else if ($response->priority == "P3") {
			$response->priority = "Low";
		}
		if ($response->status == "Open_New") {
			$response->status = "New";
		} else if ($response->status == "Open_Assigned") {
			$response->status = "Assigned";
		} else if ($response->status == "Open_Pending Input") {
			$response->status = "Pending Input";
		}
		echo '
		<div id="profileDiv"><div>
		<div id="loanDetails">
		 <div id=""> 
		 <table style="width:100%">
		 <tr>
		   <th colspan="4" style="background-color: rgb(270, 140, 0); color: white; border-top-left-radius: 10px; border-top:none;border-left:none;text-align: center;border-top-right-radius: 10px; border-top:none;border-right:none;"><span>Case Details</span></th>
		 </tr>
		 <tr>
		   <td colspan="2"><strong>Case Number: ' . $response->case_number . '</strong></td>
		   <td colspan="2"><strong>Subject:  ' . $response->name . '</strong> </td>
		 </tr>
		 <tr>
		   <td><strong>Priority:</strong></td>
		   <td><span> <strong> ' . $response->priority . ' </strong></span></td>
		   <td><strong>Status:</strong></td>
		   <td><span><strong> ' . $response->status . ' </strong></span></td>
		 </tr>
		 <tr>
		   <td><strong>Account Name:</strong></td>
		   <td><span> <strong> ' . $response->Account_Name . '</strong></span></td>
		   <td><strong>Date Creadted:</strong></td>
		   <td><strong> ' . $response->date_entered . ' </strong></td>
		 </tr>
		 <tr>
		   <td><strong>Description:</strong></td>
		   <td colspan="3"> ' . nl2br($response->description) . '</td>
		 </tr>
		 <tr>
		   <td><strong>Resolution:</strong></td>
		   <td colspan="3">  ' . nl2br($response->resolution) . ' </td>
		   
		 </tr>					
		   </table>
		 </div>
	 </div>
	 </div>
	 <table style="width:100% border:1px solid black;">
		 <tr>
		   <th colspan="4" style="background-color: rgb(270, 140, 0); color: white; border-top-left-radius: 10px; border-top:none;border-left:none;text-align: center;border-top-right-radius: 10px; border-top:none;border-right:none;"><span>Notes / Updates</span></th>
		 </tr>';

		$casesUpdatesParameters = array(
			"session" => $loginArr,
			"cases" => array(
				"caseID" => $_REQUEST['caseID'],
				"version" => "1"
			),
			"application_name" => "Cases",
			"name_value_list" => array(),
		);
		$response = $this->call("caseUpdates", $casesUpdatesParameters);
		if ($response != "" && $response != "NULL") {
			foreach ($response as $caseUpdates) {
				$start = $caseUpdates->date_entered;
				$newDate = date('Y-m-d H:i', strtotime('+5 hour', strtotime($start)));
				if ($caseUpdates->c_fullname != " ") {
					echo '<tr style="background:#BBE6A5; border-bottom:2px solid white;">
					<td><strong> ' . $caseUpdates->c_fullname . ' ' . $newDate . '</strong> </br>' . $caseUpdates->name . '</td>
				 </tr>';
				} else {
					echo '<tr style="background:#A5E6E6; border-bottom:2px solid white;">
					<td><strong> ' . $caseUpdates->A_fullname . ' ' . $newDate . '</strong> </br>' . $caseUpdates->name . '</td>
				 </tr>';
				}
			}
			echo '</table>';
			echo '<table width="100%">
					<tr>
					<td style="border: none">
					<form action="' . home_url() . '/wp-admin/admin-ajax.php" method="post">
					<input type="hidden" name="action" value="caseUpdates">
					<input type="hidden" name="caseID" value="' . $_REQUEST['caseID'] . '">
					<label>Customer Notes.</label>
					<textarea name="description" rows="4" style="width: 100%;" required></textarea>
                    </td></tr>
					<tr>
					<td style="text-align: center; border: none;">
					<input type="submit" value="Update Notes" id="caseUpdates" onclick="alertfunc();">
					</form>
					</td>
					</tr>					
					</table>';
		} else {
			echo '
			<tr>
			<td style="text-align:center;"><span>Sorry No Notes Available</span></td>
			</tr>
			</table>';
			echo '<table width="100%">
					<tr>
					<td style="border: none">
					<form action="' . home_url() . '/wp-admin/admin-ajax.php" method="post">
					<input type="hidden" name="action" value="caseUpdates">
					<input type="hidden" name="caseID" value="' . $_REQUEST['caseID'] . '">
					<label>Customer Notes.</label>
					<textarea name="description" rows="4" style="width: 100%;" required></textarea>
                    </td></tr>
					<tr>
					<td style="text-align: center; border: none;">
					<input type="submit" value="Update Notes" id="caseUpdates" onclick="alertfunc();">
					</form>
					</td>
					</tr>					
					</table>';
		}
		echo '</table>
				 </div>';
	}
	function updateCaseNotes($parameters)
	{
		$caseID = $_POST['caseID'];
		$loginArr = $this->doAPILogin();
		unset($_POST['action']);
		$contactID = $_SESSION['portal']["userContactID"];
		$assignUserID = $_SESSION['portal']["assigned_user_id"];
		$set_entry_parameters = array(
			"session" => $loginArr,
			"module_name" => "AOP_Case_Updates",
			"name_value_list" => array(
				array("name" => "name", "value" => $_POST['description']),
				array("name" => "description", "value" => $_POST['description']),
				array("name" => "contact_id", "value" => $contactID),
				array("name" => "assigned_user_id", "value" => $assignUserID),
				array("name" => "internal", "value" => '0'),
				array("name" => "case_id", "value" => $caseID),

			),
		);
		$res = $this->call("set_entry", $set_entry_parameters);
		wp_redirect(get_permalink(get_page_by_title("Case Details")) . '?caseID=' . $caseID . '&note_created=1');
	}
	function createCasePage_func()
	{
		if (!isset($_SESSION['portal'])) {
			wp_redirect(home_url());
		}

		$loginArr = $this->doAPILogin();
		$contactID = $_SESSION['portal']["userContactID"];
		$accountID = $_SESSION['portal']["account_id"];
		echo $this->leftnav();
		echo '<div id="profileDiv">	 
		<div id="loanDetails">
		 <div id=""> 					
					<form action="' . home_url() . '/wp-admin/admin-ajax.php" method="post">
					<input type="hidden" name="action" value="caseCreate">
					<input type="hidden" name="accountID" value="' . $accountID . '">
					<label>Subject:</label>
					<input type="text" name="subject" required>
					<label>Priority:</label>
					<select name="priority">
						<option value="P1" selected>High</option>
						<option value="P2">Medium</option>
						<option value="P3">Low</option>
					</select>
					<label>Description:</label>
					<textarea name="description" rows="4" required></textarea>					
					<input type="submit" value="Create Case" id="caseUpdates">
					</form>					
		</div></div></div>';
	}
	function createCase_fun()
	{
		$loginArr = $this->doAPILogin();
		unset($_POST['action']);
		$contactID = $_SESSION['portal']["userContactID"];
		$assignUserID = $_SESSION['portal']["assigned_user_id"];
		$accountID = $_POST['accountID'];
		$set_entry_parameters = array(
			"session" => $loginArr,
			"module_name" => "Cases",
			"name_value_list" => array(
				array("name" => "name", "value" => $_POST['subject']),
				array("name" => "description", "value" => $_POST['description']),
				array("name" => "priority", "value" => $_POST['priority']),
				array("name" => "assigned_user_id", "value" => $assignUserID),
				array("name" => "status", "value" => 'Open_New'),
				array("name" => "contact_created_by_id", "value" => $contactID),
				array("name" => "account_id", "value" => $accountID),
			),
		);
		$res = $this->call("set_entry", $set_entry_parameters);
		wp_redirect(get_permalink(get_page_by_title("Cases")) . '?caseCreated=1');
	}
}
$ll = new sugarConnector();
