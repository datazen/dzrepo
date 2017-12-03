
<div class="col-sm-12">
	<div class="jumbotron relative">
		<h1 class="no-margin-top">{{ 'TITLE' | translate }}</h1>
		<p class="lead">{{ 'TEXT_1' | translate }}</p>
		<p class="lead">{{ 'TEXT_2' | translate }}</p>
		<hr class="my-4">
		<p>{{ 'TEXT_3' | translate }}  <span class="lead">(site:{{currentState.site}} state:{{currentState.module}})</span></p>

		<div style="position:absolute; top:50px; right:70px;">
			<form name="form" role="form" class="form-inline">
				<div class="form-group">
					<label class="control-label" for="language">Language: </label>
					<select ng-change="vm.changeLanguage()" ng-model="vm.language" name="language" id="language" class="form-control">
						<option value="en">English</option>
						<option value="es">Spanish</option>
						<option value="fr">French</option>
						<option value="de">German</option>
					</select>
				</div>	
			</form>
		</div>
	</div>
</div>