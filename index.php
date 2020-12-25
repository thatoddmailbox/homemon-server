<?php require_once("auth.inc.php"); ?>
<?php require_once("header.inc.php"); ?>
<?php
require_once("data.inc.php");
require_once("status.inc.php");

$state = get_home_state();
$recentCheckins = get_recent_checkins();
?>
<div class="container">
	<div class="mainInfo">
		<div class="mainInfoHeader display-6">
			<i class="bi bi-lg bi-<?php echo $state["status"]["icon"]; ?>-circle"></i> Your home is <em style="color: <?php echo $state["status"]["color"]; ?>;"><?php echo $state["status"]["text"]; ?></em>.
		</div>
		<div class="mainInfoSubheader lead">
			Last checkin: <?php echo $state["lastCheckinDisplay"]; ?>
		</div>
	</div>

	<div class="recentCheckins">
		<h4>Recent checkins</h4>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Time</th>
					<th scope="col">Powered?</th>
					<th scope="col">Battery level</th>
					<th scope="col">Battery voltage</th>
					<th scope="col">IP</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($recentCheckins as $checkin) { ?>
					<tr>
						<td><?php echo relative_time_html($checkin["clientTimestamp"]); ?></td>
						<td><?php echo ($checkin["powered"] == 1 ? "yes" : "no"); ?></td>
						<?php if ($checkin["batteryLevel"] == -1) { ?>
							<td>error</td>
						<?php } else { ?>
							<td><?php echo htmlspecialchars($checkin["batteryLevel"]) ?>%</td>
						<?php } ?>
						<?php if ($checkin["batteryVoltage"] == -1) { ?>
							<td>error</td>
						<?php } else { ?>
							<td><?php echo htmlspecialchars($checkin["batteryVoltage"] / 1000) ?> V</td>
						<?php } ?>
						<td>
							<?php echo htmlspecialchars($checkin["ip"]) ?>
							(<?php if ($checkin["transport"] == 1) {
								echo "UDP";
							} else {
								echo "HTTP";
							} ?>)
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once("footer.inc.php"); ?>