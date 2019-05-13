<div id="content-wrapper">
	<!-- Content HTML goes here -->
	<div class="mui-container-fluid">
		<div class="mui--appbar-height"></div>
		<br>
		<br>
		<div class="mui-row">
			<div class="mui-col-md-6 mui-col-md-offset-3 mui--text-center">
				<div class="mui--text-display3"><a href="https://www.malcare.com"><img src="<?php echo plugins_url($this->getPluginLogo(), __FILE__); ?>" /></a></div>
			</div>
			<?php if ($this->bvmain->isConfigured()) { ?>
				<div class="mui-col-md-6 mui-col-md-offset-3" style="padding-top:2%;">
					<div class="mui-panel mui--text-center" style="margin-bottom:0!important;background-color:#4caf50;">
						<div class="mui--text-title mui--text-light">MalCare Protection Activated</div>
					</div>
				</div>
				<div class="mui-col-md-6 mui-col-md-offset-3">
					<div class="mui-panel">
						<div class="mui--text-body1">
							The plugin monitors the traffic to the site, such as login attempts, visits and errors. It then records this data into your WordPress database.
							The MalCare servers at regular intervals contact the plugin on your site to get the status of the above data. It then collates this data, along with the data from all the other sites on our network. This is then intelligently used to prevent attacks on all the sites on our network.
						</div>
					</div>
				</div>
				<div class="mui-col-md-6 mui-col-md-offset-3" style="padding-top:2%;">
					<div class="mui-panel">
						<div class="mui--text-title">MalCare Stats</div>
						<div class="mui--text-body1">Click on following button to view detailed security statistics.</div>
						<a class="mui-btn mui-btn--raised mui-btn--primary" href=<?php echo $this->bvmain->authenticatedUrl('/malcare/access')?> target="_blank">Visit Dashboard</a>
					</div>
				</div>
			<?php } else { ?>
				<div class="mui-col-md-6 mui-col-md-offset-3" style="padding-top:2%;">
					<div class="mui-panel">
						<div class="mui--text-title">Stop hackers from touching your site</div>
						<div class="mui--text-body1">Once you enter the email below, MalCare will automatically start protecting your website.</div>
					</div>
				</div>
				<div class="mui-col-md-6 mui-col-md-offset-3" style="padding-top:1%;">
					<div class="mui-panel">
						<div class="mui--text-title">Enter email to get started</div>
						<div class="mui--text-body1">Enter your email to get started. MalCare will then automatically enable firewall, login protection and website scanner.</div>
						<form dummy=">" action="<?php echo $this->bvmain->appUrl(); ?>/home/mc_signup" style="padding-top:10px;"	method="post" name="signup">
							<input type='hidden' name='bvsrc' value='wpplugin' />
							<input type='hidden' name='origin' value='protect' />
							<?php echo $this->siteInfoTags(); ?>
							<input type="text" id="email" name="email" style="height: 35px;width:70%;" value="<?php echo get_option('admin_email');?>"><br><br>
							<input type="checkbox" name="consent" value="1"/>I agree to MalCare <a href="https://www.malcare.com/tos" target="_blank" rel="noopener noreferrer">Terms of Service</a> and <a href="https://www.malcare.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a><br><br>
						<button class="mui-btn mui-btn--raised mui-btn--primary" type="submit" style="margin-left:10px;">Get started</button>
						</form>
					</div>
				</div>
			<?php	} ?>
		</div>
	</div>
</div>
<footer>
  <div class="mui-container mui--text-center">
	Made with ♥ by <a href="https://blogvault.net"><img src="<?php echo plugins_url('../img/bv.png', __FILE__); ?>" /></a>
  </div>
</footer>