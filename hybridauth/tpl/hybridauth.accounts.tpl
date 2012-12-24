<!-- BEGIN: MAIN -->
<table>
	<!-- BEGIN: HYBRID_ACCOUNT -->
	<tr>
		<td>
			<a href="{HYBRID_CONNECT_URL}" title="{PHP.L.hybridauth_connect}"><img src="http://cdn1.iconfinder.com/data/icons/socialmediaicons_v120/48/{HYBRID_ACCOUNT_CODE}.png" alt="{HYBRID_ACCOUNT_NAME}" /></a>
		</td>
		<td>
			<!-- IF {HYBRID_ACCOUNT_LINKED} -->
			<a href="{HYBRID_ACCOUNT_PROFILE}" title="{PHP.L.Profile}">{HYBRID_ACCOUNT_PROFILE}</a><br />
			<!-- ENDIF -->
			<a href="{HYBRID_ACCOUNT_LINK_URL}">{HYBRID_ACCOUNT_ACTION}</a>
		</td>
		<td>
			<span class="desc">{HYBRID_ACCOUNT_STATE}</span>
			<!-- IF {HYBRID_ACCOUNT_LINKED} -->
			<br />
			<a href="{HYBRID_ACCOUNT_CONNECT_URL}">{HYBRID_ACCOUNT_CONNECT}</a>
			<!-- ENDIF -->
		</td>
	</tr>
	<!-- END: HYBRID_ACCOUNT -->
</table>
<!-- END: MAIN -->