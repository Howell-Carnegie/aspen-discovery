AspenDiscovery.IndexingClass = (function () {
	return {

		indexingClassSelect: function (id) {
			//Hide all
			$(".form-group").each(function () {
				$(this).hide();
			});

			//Show Class Select
			$("#propertyRowid").show();
			$("#propertyRowindexingClass").show();
			$(".btn-group").parent().show();


			//Config per Class
			var ilsOptions = {
				//Common for all classes
				commonFields: ['propertyRowid', 'propertyRowname', 'propertyRowmarcPath', 'propertyRowfilenamesToInclude', 'propertyRowmarcEncoding', 'propertyRowindividualMarcPath', 'propertyRownumCharsToCreateFolderFrom', 'propertyRowcreateFolderFromLeadingCharacters', 'propertyRowgroupingClass', 'propertyRowrecordDriver', 'propertyRowcatalogDriver', 'propertyRowrecordUrlComponent', 'propertyRowprocessRecordLinking', 'propertyRowrecordNumberTag', 'propertyRowrecordNumberSubfield', 'propertyRowrecordNumberPrefix', 'propertyRowcustomMarcFieldsToIndexAsKeyword', 'propertyRowtreatUnknownLanguageAs', 'propertyRowtreatUndeterminedLanguageAs', 'propertyRowsuppressRecordsWithUrlsMatching', 'propertyRowdetermineAudienceBy', 'propertyRowaudienceSubfield', 'propertyRowtreatUnknownAudienceAs', 'propertyRowdetermineLiteraryFormBy', 'propertyRowliteraryFormSubfield', 'propertyRowhideUnknownLiteraryForm', 'propertyRowhideNotCodedLiteraryForm', 'propertyRowitemSection', 'propertyRowsuppressItemlessBibs', 'propertyRowitemTag', 'propertyRowitemRecordNumber', 'propertyRowuseItemBasedCallNumbers', 'propertyRowcallNumberPrestamp', 'propertyRowcallNumber', 'propertyRowcallNumberCutter', 'propertyRowcallNumberPoststamp', 'propertyRowlocation', 'propertyRowincludeLocationNameInDetailedLocation', 'propertyRownonHoldableLocations', 'propertyRowlocationsToSuppress', 'propertyRowsubLocation', 'propertyRowshelvingLocation', 'propertyRowcollection', 'propertyRowcollectionsToSuppress', 'propertyRowvolume', 'propertyRowitemUrl', 'propertyRowbarcode', 'propertyRowstatus', 'propertyRownonHoldableStatuses', 'propertyRowstatusesToSuppress', 'propertyRowtreatLibraryUseOnlyGroupedStatusesAsAvailable', 'propertyRowtotalCheckouts', 'propertyRowlastYearCheckouts', 'propertyRowyearToDateCheckouts', 'propertyRowtotalRenewals', 'propertyRowiType', 'propertyRownonHoldableITypes', 'propertyRowiTypesToSuppress', 'propertyRowdueDate', 'propertyRowdueDateFormat', 'propertyRowdateCreated', 'propertyRowdateCreatedFormat', 'propertyRowlastCheckinDate', 'propertyRowlastCheckinFormat', 'propertyRowformat', 'propertyRoweContentDescriptor', 'propertyRowdoAutomaticEcontentSuppression', 'propertyRownoteSubfield', 'propertyRowformatMappingSection', 'propertyRowformatSource', 'propertyRowfallbackFormatField', 'propertyRowspecifiedFormat', 'propertyRowspecifiedFormatCategory', 'propertyRowspecifiedFormatBoost', 'propertyRowcheckRecordForLargePrint', 'propertyRowformatMap', 'propertyRowstatusMappingSection', 'propertyRowstatusMap', 'propertyRoworderTag', 'propertyRoworderStatus', 'propertyRoworderLocationSingle', 'propertyRoworderLocation', 'propertyRoworderCopies', 'propertyRoworderCode3', 'propertyRowregroupAllRecords', 'propertyRowrunFullUpdate', 'propertyRowlastUpdateOfChangedRecords', 'propertyRowlastUpdateOfAllRecords', 'propertyRowlastChangeProcessed', 'propertyRowfullMarcExportRecordIdThreshold', 'propertyRowlastUpdateFromMarcExport', 'propertyRowtranslationMaps', 'FloatingSave', 'propertyRowindex856Links', 'propertyRowincludePersonalAndCorporateNamesInTopics'],
				//Specific per class
				Koha: ['propertyRowlastUpdateOfAuthorities'],
				Evolve: [],
				ArlingtonKoha: ['propertyRowlastUpdateOfAuthorities'],
				CarlX: [],
				Folio: [],
				III: ['propertyRowbCode3sToSuppress', 'propertyRowiCode2', 'propertyRowuseICode2Suppression', 'propertyRowiCode2sToSuppress', 'propertyRoworderSection', 'propertyRowsierraSection', 'propertyRoworderRecordsStatusesToInclude', 'propertyRowhideOrderRecordsForBibsWithPhysicalItems', 'propertyRoworderRecordsToSuppressByDate', 'propertyRowsierraFieldMappings'],
				Symphony: ['propertyRowlastVolumeExportTimestamp'],
				Polaris: [],
				Evergreen: ['propertyRowevergreenOrgUnitSchema', 'propertyRowevergreenSection']
			};

			//Show rows for selected class
			var selectedIndexingClass = $("#indexingClassSelect").val();
			var selectedIndexingClassText = $("#indexingClassSelect option:selected").text();

			if (selectedIndexingClass !== '...' && selectedIndexingClass !== '') {
				var iterator = ilsOptions[selectedIndexingClass];
				iterator = $.merge(ilsOptions['commonFields'], iterator);
				iterator.forEach(function (value) {
					$("#" + value).show();
				});
			}
		}
	}
}(AspenDiscovery.IndexingClass || {}));
