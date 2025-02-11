{if isset($cluster.showMoreFacetPopup) && $cluster.showMoreFacetPopup}
    {foreach from=$cluster.list item=thisFacet name="narrowLoop"}
        {if $smarty.foreach.narrowLoop.iteration == ($cluster.valuesToShow + 1)}
            {* Show More link if facet isn't searchable*}
            {if !$hasSearchableFacets}
				<div class="facetValue" id="more{$title}"><a href="#" onclick="AspenDiscovery.ResultsList.moreFacets('{$title}'); return false;">{translate text='more' isPublicFacing=true} ...</a></div>
            {/if}
            {* Start div for hidden content*}
			<div class="narrowGroupHidden" id="narrowGroupHidden_{$title}" style="display:none">
        {/if}
        {if !empty($thisFacet.isApplied)}
			<div class="facetValue"><i class="fas fa-check-circle fa-lg text-success" style="vertical-align: middle"></i> {$thisFacet.display} <a href="{$thisFacet.removalUrl|escape}" class="removeFacetLink">({translate text='remove' isPublicFacing=true})</a></div>
        {else}
			<div class="facetValue">{if $thisFacet.url !=null}<a href="{$thisFacet.url|escape}">{/if}{$thisFacet.display}{if $thisFacet.url !=null}</a>{/if}{if $facetCountsToShow == 1 || ($facetCountsToShow == 2 && empty($thisFacet.countIsApproximate))}{if $thisFacet.count != ''}&nbsp;({if !empty($thisFacet.countIsApproximate)}{/if}{$thisFacet.count|number_format}){/if}{/if}</div>
        {/if}
    {/foreach}
    {if $smarty.foreach.narrowLoop.total > $cluster.valuesToShow}
		<div class="facetValue">
			<a href="#" onclick="AspenDiscovery.ResultsList.lessFacets('{$title}'); return false;">{translate text='less' isPublicFacing=true} ...</a>
		</div>
		</div>{* closes hidden div *}
    {/if}
    {* Show more list *}
    {if $hasSearchableFacets}
		<div class="facetValue" id="more{$title}"><a href="#" onclick="AspenDiscovery.Searches.showSearchFacetPopup('{$searchId}', '{$title}'); return false;">{translate text='more' isPublicFacing=true} ...</a></div>
    {else}
		<div class="facetValue" id="more{$title}"><a href="#" onclick="AspenDiscovery.ResultsList.moreFacetPopup('More {$cluster.displayNamePlural|escape}', '{$title}'); return false;">{translate text='more' isPublicFacing=true} ...</a></div>
		<div id="moreFacetPopup_{$title}" style="display:none">
			<p>{translate text="Please select one of the items below to narrow your search by %1%." 1=$cluster.label isPublicFacing=true}</p>
			<div class="container-12">
				<div class="row moreFacetPopup">
                    {foreach from=$cluster.sortedList item=thisFacet name="narrowLoop"}
						<div class="col-tn-12 standardFacet">{if $thisFacet.url !=null}<a href="{$thisFacet.url|escape}">{/if}{$thisFacet.display}{if $thisFacet.url !=null}</a>{/if}{if $facetCountsToShow == 1 || ($facetCountsToShow == 2 && empty($thisFacet.countIsApproximate))}{if $thisFacet.count != ''}&nbsp;({if !empty($thisFacet.countIsApproximate)}{/if}{$thisFacet.count|number_format}){/if}{/if}</div>
                    {/foreach}
				</div>
			</div>
		</div>
    {/if}
{else}
    {foreach from=$cluster.list item=thisFacet name="narrowLoop"}
        {if $smarty.foreach.narrowLoop.iteration == ($cluster.valuesToShow + 1)}
            {* Show More link*}
			<div class="facetValue" id="more{$title}"><a href="#" onclick="AspenDiscovery.ResultsList.moreFacets('{$title}'); return false;">{translate text='more' isPublicFacing=true} ...</a></div>
            {* Start div for hidden content*}
			<div class="narrowGroupHidden" id="narrowGroupHidden_{$title}" style="display:none">
        {/if}
        {if !empty($thisFacet.isApplied)}
			<div class="facetValue"><i class="fas fa-check-circle fa-lg text-success" style="vertical-align: middle"></i> {$thisFacet.display} <a href="{$thisFacet.removalUrl|escape}" class="removeFacetLink">({translate text='remove' isPublicFacing=true})</a></div>
        {else}
			<div class="facetValue">{if $thisFacet.url !=null}<a href="{$thisFacet.url|escape}">{/if}{$thisFacet.display}{if $thisFacet.url !=null}</a>{/if}{if $facetCountsToShow == 1 || ($facetCountsToShow == 2 && empty($thisFacet.countIsApproximate))}{if $thisFacet.count != ''}&nbsp;({if !empty($thisFacet.countIsApproximate)}{/if}{$thisFacet.count|number_format}){/if}{/if}</div>
        {/if}
    {/foreach}
    {if $smarty.foreach.narrowLoop.total > $cluster.valuesToShow}
		<div class="facetValue">
			<a href="#" onclick="AspenDiscovery.ResultsList.lessFacets('{$title}'); return false;">{translate text='less' isPublicFacing=true} ...</a>
		</div>
		</div>{* closes hidden div *}
    {/if}
{/if}