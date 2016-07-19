<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Rules of Conduct Agreement</h4>
			<p><a href="http://MISSIONS.ME" target="_blank" rel="noreferrer">MISSIONS.ME</a> TEAM CULTURE</p>
			<p>AS A <a href="http://MISSIONS.ME" target="_blank" rel="noreferrer">MISSIONS.ME</a> TEAM MEMBER, I PROMISE TO DO MY BEST TO CONTRIBUTE TO THE FOLLOWING TEAM CULTURE POINTS WHILE ON THIS TRIP.</p>
			<p>HONOR - I will honor my leaders, fellow team members, and those I am ministering to.</p>
			<p>SAFETY - I will put the safety of myself and others above all else.</p>
			<p>FUN - I will strive to create life-long memories and experiences that are both impacting and fun.</p>
			<p>FAITH - I will keep an attitude of faith in myself and my fellow team members.</p>
			<p>UNITY - I will do my best to bring unity and camaraderie to my team.</p>
			<p>RULES &amp; GUIDELINES</p>
			<p>BREAKING ANY OF THE FOLLOWING RULES ARE GROUNDS FOR IMMEDIATE DISMISSAL FROM THIS <a href="http://MISSIONS.ME" target="_blank" rel="noreferrer">MISSIONS.ME</a> TRIP:</p>
			<ol>
				<li>Illegal acts of any kind including assault, possession of illegal drugs or weapons.</li>
				<li>Consumption or possession of alcoholic beverages or tobacco products of any kind.</li>
				<li>Departing the designated team housing area alone or unsupervised without the consent of a Team Leader.</li>
				<li>Departing your team or assigned group at any time without the consent of a Team Leader.</li>
			</ol>
			<p>BREAKING ANY OF THE FOLLOWING RULES ARE GROUNDS FOR IMMEDIATE DISCIPLINARY ACTION AND POSSIBLE DISMISSAL FROM THIS <a href="http://MISSIONS.ME" target="_blank" rel="noreferrer">MISSIONS.ME</a> TRIP:</p>
			<ol>
				<li>Fighting with fellow team members.</li>
				<li>Romantic displays of affection with a member of the opposite sex that is not your spouse.</li>
				<li>Disorderly or deliberately disobedient conduct.</li>
				<li>Profanity or temperamental outbursts.</li>
				<li>Disrespectful attitude towards locals or international team members.</li>
				<li>Disrespectful attitude towards team leadership.</li>
			</ol>
			<p>As a participant of a <a href="http://Missions.Me" target="_blank">Missions.Me</a> trip, I have read and fully understand the rules and regulations above. I also understand that I must comply with all <a href="http://Missions.Me" target="_blank" rel="noreferrer">Missions.Me</a> rules and regulations in order to make my missions experience the best it can be. If I choose not to comply with these rules, I understand that I am subject to the consequences and even dismissal from the mission field AT MY OWN EXPENSE.</p>
		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="rocaAgree">
					I have read and agree to the Rules of Conduct Agreement.
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'roca',
		data(){
			return {
				title: 'Rules of Conduct Agreement',
				rocaAgree: false
			}
		},
		watch:{
			'rocaAgree'(val, oldVal) {
				this.$dispatch('roca-agree', val)
			}
		},
		activate(done){
			$('html, body').animate({scrollTop : 0},300);
			done();
		}
	}
</script>
