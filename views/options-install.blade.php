<div class="form-group">
	<label class="custom-control custom-checkbox form-group mb-1 mr-0 d-block">
		<input type="hidden" name="extension-manager" value="0"/>
		<input type="checkbox" name="extension-manager"
		       class="custom-control-input form-check-input" value="1"
		       @if (array_get($app->getOptions(), 'extension-manager', true)) checked="CHECKED" @endif />
		<span class="custom-control-indicator"></span>
		Enable <a href="https://docs.flarum.org/extensions/">Extension Manager</a>
		&ndash;
		<span class="ui-action-tooltip" data-toggle="tooltip"
		      title="Add extensions within {{ $app->getName() }}'s dashboard">
            <abbr class="small text-uppercase">Help</abbr>
        </span>
	</label>
</div>