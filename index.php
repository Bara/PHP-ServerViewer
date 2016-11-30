<?php
	require_once "_config.php";

	if($active == false)
		die("Under construction!");
	
	if($debug)
		ini_set('display_errors', '1');
	else if($debug == false && $debugErrors)
		ini_set('display_errors', '1');
	else if($debug == false && $debugErrors == false)
		ini_set('display_errors', '0');

	$cplayers = 0;
	$cmaxplayers = 0;
	$servers = 0;
	$sonline = 0;

	$aServers = array();
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>FOTG Server Viewer</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/form.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<link href="css/generics.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	 <?php
		$aBG = array("violate", "blue", "chrome", "city", "greenish", "kiwi", "lights", "night", "ocean", "sunny", "sunset", "yellow");
		$i = rand(0, count($aBG)-1);
		$selectedBg = "$aBG[$i]";
	?>

	<body id="skin-blur-<?php echo $selectedBg; ?>">
		<div style="margin-left:auto;margin-right:auto;width:65%;">
			<center><img src="img/header.png" align="middle" class="img-center img-responsive" alt="" /></center>

				<p style="text-align:center">
					<a class="btn btn-default btn-alt m-r-5" href="<?php echo $forum ?>" role="button" target="_blank"><img src='img/forum.png' alt='' /> Forum</a>
					<a class="btn btn-default btn-alt m-r-5" href="<?php echo $bans ?>" role="button" target="_blank"><img src='img/bans_l.png' alt='' /> Bans</a>
				</p>
				<?php
					$mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldb, $sqlport);

					if (mysqli_connect_errno())
					{
						printf("<strong><font color='red'>Connect failed: %s\n</font></strong>", mysqli_connect_error());
						exit();
					}

					if ($result = $mysqli->query($scanquery))
					{
						while ($row = $result->fetch_object())
							$last = $row->last;
						$result->close();
					}
					$mysqli->close();
				?>
			
						<?php
							$mysqli = new mysqli($sqlhost, $sqluser, $sqlpass, $sqldb, $sqlport);

							if (mysqli_connect_errno())
							{
								printf("<strong><font color='red'>Connect failed: %s\n</font></strong>", mysqli_connect_error());
								exit();
							}

							if ($result = $mysqli->query($query))
							{
								while ($row = $result->fetch_object())
								{
									$ip             = $row->ip;
									$city           = $row->city;
									$port           = $row->port;
									$id             = $row->id;
									$countrycode    = $row->countrycode;
									$countryname    = $row->countryname;
									$active         = $row->active;
									$count          = $row->count;
									$display        = $row->display;
									$description    = $row->description;
									$sourceBansLink = $row->sourceBans;
									$gameMELink     = $row->gameME;

									$data        = unserialize($row->data);
									$players     = unserialize($row->players);

									if($active == 0)
										continue;

									if($display == 0)
										continue;

									ini_set('date.timezone', 'Europe/Berlin');

									if($debug == 1)
									{
										echo '<pre>';
										echo "IP: $ip - Port: $port <br/>";
										print_r($data);
										print_r($players);
										echo '</pre>';
									}

									$size = count($players);
									$nplayers = 0;
									$mplayers = 0;
									$online = 0;

									if(is_array($data))
									{
										if($count)
											$status = "<span class='label label-success'>Online</span>";
										else
											$status = "<span class='label label-default'>Online</span>";
										$sonline++;
										$hostname = $data["HostName"];
										$online = 1;
									}
									else
									{
										$status = "<span class='label label-danger'>Offline</span>";
										$hostname = "<font color='tomat2'><strong>$description</strong></font>";
									}

									if($data["ModDir"] == "csgo")
										$game = "<img src=\"img/games/" . $data["ModDir"] . ".png\" alt=\"" . $data["HostName"] . "\"/>";
									else if($data["ModDir"] == "cstrike")
										$game = "<img src=\"img/games/" . $data["ModDir"] . ".png\" alt=\"" . $data["HostName"] . "\"/>";
									else
										$game = "";

									// Save players count
									if($data["Players"] > 0)
										$nplayers = $data["Players"];

									// Fix a bug (no players on server)
									if($nplayers == "")
										$nplayers = "0";

									// Save players count
									if($data["MaxPlayers"] > 0)
										$mplayers = $data["MaxPlayers"];

									// Fix a bug (no players on server)
									if($mplayers == "")
										$mplayers = "0";

									// Remove bots from stats (no fake data please)
									if($bots == "1")
										if($data["Bots"] > 0)
											$nplayers = $nplayers - $data["Bots"];
									
									if($mplayers > "0")
									{
										$onepercent = 100 / $mplayers;
										if($nplayers > "0")
											$percent = round($nplayers * $onepercent, 0);
										else
											$percent = "0";
										
									}
									else
									{
										$onepercent = "0";
										$percent = "0";
									}
										

									if($percent >= $danger)
										$color = "danger";
									else if($percent >= $warning && $percent < $danger)
										$color = "warning";
									else if($percent < $warning)
										$color = "success";

									$flag = "<img src=\"img/flags/" . $countrycode . ".png\" alt=\"" . $countryname . "\"/>";

									if($online && $count)
									{
										$cplayers += $nplayers;
										$cmaxplayers += $mplayers;
									}

									$servers++;
									$sourceBans = "";
									$gameME = "";

									if($enableSteam)
										$steam = "<a href='steam://connect/$ip:$port/' target='_blank'><img src='img/steam.png' alt='' /></a>";

									if($enableGametracker)
										$gametracker = "<a href='http://www.gametracker.com/server_info/$ip:$port/' target='_blank'><img src='img/gt.png' alt='' /></a>";

									if($enableHLSW)
										$hlsw = "<a href='hlsw://$ip:$port/' target='_blank'><img src='img/hlsw.png' alt='' /></a>";

									if(strlen($sourceBansLink) > 1)
										$sourceBans = "<a href='$sourceBansLink' target='_blank'><img src='img/bans.png' alt='' /></a>";

									if(strlen($gameMELink) > 1)
										$gameME = "<a href='$gameMELink' target='_blank'><img src='img/gameme.png' alt='' /></a>";

									$aServer = [
										"status"     => $status,
										"flag"       => $flag,
										"game"       => $game,
										"hostname"   => $hostname,
										"map"        => $data["Map"],
										"nplayers"   => $nplayers,
										"mplayers"   => $mplayers,
										"color"      => $color,
										"ip"         => $ip,
										"port"       => $port,
										"percent"    => $percent,
										"steam"      => $steam,
										"gametracker"=> $gametracker,
										"hlsw"       => $hlsw,
										"sourceBans" => $sourceBans,
										"gameME"     => $gameME,
									];

									array_push($aServers, $aServer);
								}
								$result->close();
							}
							$mysqli->close();
						?>

			<?php echo "<center><h3 class='block-title'><strong>Last Scan:</strong> " . date('d M Y H:i:s', $last) . " | <strong>Servers online:</strong> $sonline <strong>Servers:</strong> $servers | <strong>Spieler online:</strong> $cplayers <strong>Max. Spieler:</strong> $cmaxplayers</h3>"; ?>

			<div class='table-responsive'>
				<table class='table table-bordered table-hover table-condensed'>
					<thead>
						<tr>
							<th style='text-align:center'><strong>Status</strong></th>
							<th style='text-align:center'><strong>Location</strong></th>
							<th style='text-align:center'><strong>Game</strong></th>
							<th style='text-align:center'><strong>Name</strong></th>
							<th style='text-align:center'><strong>Map</strong></th>
							<th style='text-align:center'><strong>Players</strong></th>
							<th style='text-align:center'><strong>IP:Port</strong></th>
							<th style='text-align:center'><strong>Connect</strong></th>
						</tr>
					</thead>
					<tbody>

			<?php
				function orderBy(&$data, $field)
				{
					$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
					usort($data, create_function('$b,$a', $code));
				}

				orderBy($aServers, 'nplayers');

				foreach($aServers as $server)
				{
					echo "
						<tr>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[status]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[flag]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[game]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[hostname]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[map]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>
								<div class='m-b-10'>
									$server[nplayers]/$server[mplayers]
									<div class='progress'>
										<div class='progress-bar progress-bar-$server[color] progress-bar-striped active' role='progressbar' aria-valuenow='$server[nplayers]' aria-valuemin='0' aria-valuemax='100' style='width: $server[percent]%'></div>
									</div>
								</div>
								</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[ip]:$server[port]</td>
							<td style='vertical-align:middle;text-align:center;background-color:rgba(0,0,0,0.3);'>$server[steam] $server[gametracker] $server[hlsw] $server[sourceBans] $server[gameME]</td>
						</tr>";
				}
			?>

					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
