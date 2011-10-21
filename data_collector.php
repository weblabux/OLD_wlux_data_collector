<?php

class data_collector
{

	public function rewrite($included_path)
	{
		echo '
			<script type="text/javascript" src="', $included_path, '"></script>
			<script type="text/javascript">
				$(document).ready(function()
				{
					$("a[href]").each(function()
					{
						var data_variables = \'from_title=\' + document.title + \'&from=\' + location.href;
						if(this.href.indexOf("?") == -1)
							this.href = this.href + \'?\' + data_variables + \'&to=\' + this.href;
						else
							this.href = this.href + \'&\' + data_variables + \'&to=\' + this.href;
					});
					$("form").each(function()
					{
						from_title = document.createElement("input");
						from_title.setAttribute("type","hidden");
						from_title.setAttribute("name","from_title");
						from_title.setAttribute("value",document.title);
						this.appendChild(from_title);
						from = document.createElement("input");
						from.setAttribute("type","hidden");
						from.setAttribute("name","from");
						from.setAttribute("value",location.href);
						this.appendChild(from);
						to = document.createElement("input");
						to.setAttribute("type","hidden");
						to.setAttribute("name","to");
						to.setAttribute("value",this.action);
						this.appendChild(to);
					});
				});
			</script>';
	}

	public function land ()
	{
		if (isset( $_REQUEST['session_id'] ))
		{
			session_start ();
			$_SESSION['session_id'] = $_REQUEST['session_id'];
			$this->check_session();
		}
	}

	public function check_session()
	{
		session_start ();
		if (!isset($_SESSION['session_id']))
		{
			header('HTTP/1.1 302 Undefined Redirect');
			header('Location: about:blank');
			die();
		}
		else
		{
			// TO DO validate!
			// send request
			include('RestRequest.inc.php');
			include('config.php');
			$request = new RestRequest($base_api_url.'path_data.php', 'POST', $user, $password, array('session_id'=>$_SESSION['session_id'], 'from_url'=>$_REQUEST['from'], 'from_title'=>$_REQUEST['from_title'], 'to_url'=>$_REQUEST['to'], 'custom_variable'=>$_REQUEST[$custom_request_variable]));
			$request->execute();
			// debug:
			//echo '<pre>' . print_r($request, true) . '</pre>';
		}
	}
	
	public function leave()
	{
		include('config.php');
		session_start();
		unset ($_SESSION['session_id']);
		session_unset();
		session_destroy();
	}
}

?>
