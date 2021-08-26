{strip}
	{* Details not shown in the Top/Main Section of the Record view should be shown here *}
	{if !$showPublicationDetails && $recordDriver->getPublicationDetails()}
		<div class="row">
			<div class="result-label col-md-3">{translate text='Published'}</div>
			<div class="col-md-9 result-value">
				{implode subject=$recordDriver->getPublicationDetails() glue=", "}
			</div>
		</div>
	{/if}

	<div class="row">
		<div class="result-label col-md-3">{translate text='Language'}</div>
		<div class="col-md-9 result-value">
			{$recordDriver->getLanguage()}
		</div>
	</div>

	{if !$showISBNs && count($recordDriver->getISBNs()) > 0}
		<div class="row">
			<div class="result-label col-md-3">{translate text='ISBN'}</div>
			<div class="col-md-9 result-value">
				{implode subject=$recordDriver->getISBNs() glue=", "}
			</div>
		</div>
	{/if}

	{if $recordDriver->getAcceleratedReaderData() != null}
		{assign var="arData" value=$recordDriver->getAcceleratedReaderData()}
		<div class="row">
			<div class="result-label col-md-3">{translate text='Accelerated Reader'}</div>
			<div class="col-md-9 result-value">
				{if $arData.interestLevel}
					{$arData.interestLevel|escape}<br/>
				{/if}
				{translate text="Level %1%, %2% Points" 1=$arData.readingLevel|escape 2=$arData.pointValue|escape isPublicFacing=true}
			</div>
		</div>
	{/if}

	{if $recordDriver->getLexileCode()}
		<div class="row">
			<div class="result-label col-md-3">{translate text='Lexile code'}</div>
			<div class="col-md-9 result-value">
				{$recordDriver->getLexileCode()|escape}
			</div>
		</div>
	{/if}

	{if $recordDriver->getLexileScore()}
		<div class="row">
			<div class="result-label col-md-3">{translate text='Lexile measure'}</div>
			<div class="col-md-9 result-value">
				{$recordDriver->getLexileScore()|escape}
			</div>
		</div>
	{/if}

	{if $recordDriver->getFountasPinnellLevel()}
		<div class="row">
			<div class="result-label col-md-3">{translate text='Fountas &amp; Pinnell'}</div>
			<div class="col-md-9 result-value">
				{$recordDriver->getFountasPinnellLevel()|escape}
			</div>
		</div>
	{/if}

{/strip}