/* Custom JavaScript */

jQuery(function () {
  var filterList = {
    init: function () {
      jQuery(".blog-grid").mixItUp({
        selectors: {
          target: ".blog",
          filter: ".filter",
        },
        load: {
          filter: "all",
        },
      });
    },
  };
  filterList.init();
});

jQuery(function () {
    jQuery("#BlogMainWrapper .primaryBlogTitleBox.detailsPage .blogMetaDataBox .print-page").click(function () {
		var titleText = jQuery('#BlogMainWrapper .topFeatureBox .primaryBlogTitleBox.detailsPage .title').html();
		var titleTextPlain = jQuery('#BlogMainWrapper .topFeatureBox .primaryBlogTitleBox.detailsPage .title').text();
        var contents = jQuery("#printDivContent").html();
        var frame1 = jQuery('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        jQuery("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>'+titleTextPlain+'</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(titleText+contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });
});


jQuery(document).ready(function() {
	jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .up").click(function(e) {

		jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .up .fav .icons").removeClass( "icon-thumbs-up" ).addClass( "icon-spinner fa-spin" );

		var token = jQuery("#token").attr("name");
		var id = jQuery(this).attr("data-id");
		var getUrl = "index.php?option=com_blogsters&task=getSetUpVoteCount&tmpl=component";

		jQuery.ajax({
			type: "POST",
			url: getUrl,
			data: {
				token: token,
				id: id
			},
			success: function(result, status, xhr) {
				if(result){
				jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .up .fav .icons").removeClass( "icon-spinner fa-spin" ).addClass( "icon-thumbs-up" );
				if(result=='exist'){
					jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .up").css("cursor","not-allowed");
					jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .down").css("cursor","not-allowed");
				}
				}
			},
			error: function() {}
		});
	});
});

jQuery(document).ready(function() {
	jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .down").click(function(e) {

		jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .down .fav .icons").removeClass( "icon-thumbs-down" ).addClass( "icon-spinner fa-spin" );

		var token = jQuery("#token").attr("name");
		var id = jQuery(this).attr("data-id");
		var getUrl = "index.php?option=com_blogsters&task=getSetDownVoteCount&tmpl=component";

		jQuery.ajax({
			type: "POST",
			url: getUrl,
			data: {
				token: token,
				id: id
			},
			success: function(result, status, xhr) {
				if(result){
				jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .down .fav .icons").removeClass( "icon-spinner fa-spin" ).addClass( "icon-thumbs-down" );
				if(result=='exist'){
					jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .up").css("cursor","not-allowed");
					jQuery(".footerLikeSocialWrapper .uk-container .box .vote .icon .down").css("cursor","not-allowed");
				}
				}
			},
			error: function() {}
		});
	});
});
