# IF C_POPUP  #
	<p>{L_RADIO_NAME}</p>
	<p>
		<img src="{U_RADIO_IMG}" alt="{L_RADIO_NAME}" />
	</p>
	<p>
		<a class="radio-button" title="{@radio.live}" href="{PATH_TO_ROOT}/radio/RadioPlayer.php" onclick="window.open(this.href, 'RadioPlayer', 'width=400, height=300'); return false;">
			{@radio.live}
		</a>
	</p>
	<p class="center"><a href="{PATH_TO_ROOT}/radio/">{@radio.programs}</a></p>
# ELSE #
<p>{L_RADIO_NAME}</p>
	<div class="radio-mini">
			<audio controls {C_AUTOPLAY}>
				<source src="{U_NETWORK}" />
			</audio>
			# IF C_IMG #<img src="{U_RADIO_IMG}" alt="{L_RADIO_NAME}" /># ENDIF #
			<p class="center"><a href="{PATH_TO_ROOT}/radio/">{@radio.programs}</a></p>
	</div>
# ENDIF #
