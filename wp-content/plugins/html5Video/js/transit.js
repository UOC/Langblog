var pop;
var paused = true;
// var width_corr;  width correlation between timeline and seeker (keeps seeker "inside" timeline by reducing the width range (%) )
var scrubbing = false;
var notesArray = Array(); // Array of all notes
var notesArray_ = {}; // Associative version of notesArray, trying to remove it
var tracksArray = Array();
var popIds = {}; // Associative array of the Popcorn eventIds and the note_ids, used for changing start times, deleting etc.   


/******* DEBUGGING ***************/
var debug = true; // Set to true to run debug tests

if (debug == true) {
	jQuery("#debug_toggle").show();	
}

jQuery("#debug_toggle").click(function(e){
	if(jQuery("#debug_info").is(":visible")){
		jQuery("#debug_info").hide();
		jQuery("#debug_toggle").html("Show Debug Info");
	} else {
		jQuery("#debug_info").show();
		jQuery("#debug_toggle").html("Hide Debug Info");
	}
});


function setup_video(duration){ /// Called on DOM ready 
		jQuery("#debug_info").append("<div>calling setup_video(duration)</div>")
		////// SET VIEW MODE /////////////////////////

		toggleView(viewMode);

		if (notesCreate == "true" || notesCreate == true) {
			jQuery("#add_footnote").show();
			/* disable option to hide notes */
			disable_show_list();
		} else {
			if (isAdmin == "false") {
				jQuery("#add_footnote").hide();
			}					
			enable_show_list();
		}

		if (showNotesList == "true" || showNotesList == true) {
			jQuery("#notes-list").show();
		} else {
			if (isAdmin == "false") {
				jQuery("#notes-list").hide();
			}
		}

		jQuery("#progress-time").html(toHHMMSS(0));
		jQuery("#duration").html(toHHMMSS(duration));

		////// GET AND DISPLAY FOOTNOTES /////////////
	    initialize_notes(duration);  
	    initialize_footnote_form();
	    if (isAdmin == "true") {
		    setup_admin_controls();
		};


};


////////////////////////// INIT FUNCTION -- called on popcorn load /////////////////////////////////////

function initialize_notes(duration){ // GET NOTES AND ADD TO POPCORN CONSTRUCTOR //
	jQuery.ajax({
	    type: "POST",
	    url: ajaxurl,
	    data: { 'action':'get_notes', 'postID':jQuery("#post_id").val()},
	    dataType: "json",
	    cache: false,
	    success: function(data)
			{	
				notesArray = data.notes;
				tracksArray = data.tracks;

				notesArray.sort(function(a,b){return a.startTime > b.startTime ? 1 : -1})

				for (var j=0;j<notesArray.length;j++) {
					insert_note(notesArray[j]);
				}

				arrange_timeline();  
				jQuery("#loading_cover").fadeOut();

			}
	    });
}


//////////////////////////  ADMIN FXNS JS ///////////////////////////////////////////////////

function setup_admin_controls(){

	if (viewMode == "A") {
		jQuery("#admin_view_options ul li#view_A").removeClass("unselected").addClass("selected");
	} else if (viewMode == "B") {
		jQuery("#admin_view_options ul li#view_B").removeClass("unselected").addClass("selected");
	} else if (viewMode == "C") {
		jQuery("#admin_view_options ul li#view_C").removeClass("unselected").addClass("selected");
	}

	if (notesCreate == 'true') {
		jQuery("#notesCreate input:checkbox").attr("checked", true);
	} else if (notesCreate == 'false'){
		jQuery("#notesCreate input:checkbox").attr("checked", false);
	}

	if (showNotesList == 'true') {
		jQuery("#showNotesList input:checkbox").attr("checked", true);
	} else if (showNotesList == 'false'){
		jQuery("#showNotesList input:checkbox").attr("checked", false);
	}

	jQuery("#admin-controls").show();
}


jQuery('#admin_view_options ul li').click(function(e){

	var selected_view = jQuery(this).attr('id').split('view_')[1];

	if (selected_view != viewMode) { // The clicked mode is NOT the current view mode
		jQuery.ajax({
		    type: "POST",
		    url: ajaxurl,
		    data: {
				'action':'changeView',
				'postID':jQuery("#post_id").val(),
				'key': 'viewMode',
				'value': selected_view
			},
		    cache: false,
		    success: function(data)
			{
				viewMode = data;
				toggleView(data);
			}
	    });	
	}

});

jQuery('#notesCreate input:checkbox').click(function(){
	jQuery.ajax({
	    type: "POST",
	    url: ajaxurl,
	    data: {
			'action':'writeMetaVal',
			'postID':jQuery("#post_id").val(),
			'key': 'notesCreate',
			'value': jQuery(this).is(":checked")
		},
	    cache: false,
	    success: function(data)
		{
			notesCreate = data;
			if (data == 'true' || data == true) {
				jQuery("#add_footnote").show();
				disable_show_list();
			} else if (data == 'false') {
				if (isAdmin == "false") {
					jQuery("#add_footnote").hide();
				}
				enable_show_list();
			}
		}
    });	
});

jQuery('#showNotesList input:checkbox').click(function(){
	jQuery.ajax({
	    type: "POST",
	    url: ajaxurl,
	    data: {
			'action':'writeMetaVal',
			'postID':jQuery("#post_id").val(),
			'key': 'showNotesList',
			'value': jQuery(this).is(":checked")
		},
	    cache: false,
	    success: function(data)
		{
			showNotesList = data;
			// console.log(json_decode(data));
			if (data == 'true') {
				jQuery("#notes-list").show();
			} else if (data == 'false') {
				if (isAdmin == "false") {
					jQuery("#notes-list").hide();
				}
			}
		}
    });	
});

function toggleView(mode) {
	/* MODES: 
		A - Player and Notes, side-by-side; 
		B - Player and notes, 720px video and notes overlaid
		C - Player only, no notes;  */

	jQuery("#debug_info").append("<div>toggleView: " + mode + "</div>");

	var aspect = vidIntrinsicWidth / vidIntrinsicHeight;

	jQuery("#debug_info").append("<div>aspect:  " + aspect + "</div>");

	if (mode == 'A') {
		jQuery("#admin_view_options ul li#view_B").addClass("unselected");
		jQuery("#admin_view_options ul li#view_C").addClass("unselected");
		jQuery("#admin_view_options ul li#view_A").removeClass("unselected").addClass("selected");

		jQuery("#footnotediv").removeClass();	
		jQuery("#footnotediv").addClass('footnotediv_vistaA');

		jQuery("#videoViewer").css("width", "50%");
		jQuery("#videoViewer").css("float", "left");	
		var vidheight = Math.floor(jQuery("#videoViewer").outerWidth() / aspect)
		jQuery("#videoViewer").css("height", vidheight + "px");

		jQuery("#videoTimeline").show();
		jQuery("#video").removeAttr("controls")
		
		jQuery("#notesViewer").removeClass().addClass('notesaside');


		var video_height = jQuery(video).innerHeight();
		jQuery("#notesViewer").css('height', video_height + "px");	
		jQuery(".notesaside").css('width', '50%');
		jQuery("#notesControls-wrapper").show()	
		jQuery("#notesViewer").show();	

		

		jQuery(".position_selector").hide();

		jQuery(".footnote").css('position','inherit');

	} else if (mode == 'B') {
		jQuery("#admin_view_options ul li#view_A").addClass("unselected");
		jQuery("#admin_view_options ul li#view_C").addClass("unselected");
		jQuery("#admin_view_options ul li#view_B").removeClass("unselected").addClass("selected");

		jQuery("#videoViewer").css("width", "720px");
		jQuery("#videoViewer").css("float", "none");
		var vidheight = Math.floor(jQuery("#videoViewer").outerWidth() / aspect)
		jQuery("#videoViewer").css("height", vidheight + "px");

		jQuery("#videoTimeline").show();
		jQuery("#video").removeAttr("controls")

		// jQuery("#notesViewer").hide();
		jQuery("#notesViewer").removeClass().addClass('notesontop');
		jQuery("#notesViewer").show();	
		// jQuery(".notesontop").css('height', video_height + "px");

		jQuery("#footnotediv").removeClass();	
		jQuery("#footnotediv").addClass('footnotediv_vistaB');

		// jQuery("#notesControls-wrapper").hide()
		jQuery("#notesControls-wrapper").show()	
		

		var video_height = jQuery(video).innerHeight();
		var video_pos_x = jQuery("#video").position().left;
		var video_width = jQuery(video).outerWidth();

		jQuery(".notesontop").css('height', video_height + "px");
		jQuery(".notesontop").css('width', video_width + "px");
		jQuery(".notesontop").css('top', '0px');
		jQuery(".notesontop").css('left', video_pos_x + "px");
		// jQuery("#notesViewer").css('height', video_height + "px");


		jQuery(".position_selector").show();

		jQuery(".footnote").css('position','absolute');

	} else if (mode == 'C') {
		jQuery("#admin_view_options ul li#view_A").addClass("unselected");
		jQuery("#admin_view_options ul li#view_B").addClass("unselected");
		jQuery("#admin_view_options ul li#view_C").removeClass("unselected").addClass("selected");

		jQuery("#notesViewer").hide();
		jQuery("#notesControls-wrapper").hide()
		jQuery("#videoViewer").css("width", "720px");
		var vidheight = Math.floor(jQuery("#videoViewer").outerWidth() / aspect)
		jQuery("#videoViewer").css("height", vidheight + "px");

		jQuery("#videoViewer").css("float", "none");
		jQuery("#videoTimeline").hide();
		jQuery("#video").attr("controls", true)	
	}
}

function enable_show_list() {
	jQuery("#showNotesList input").removeAttr('disabled');
	jQuery("#showNotesList").css('color', '#444444');
}

function disable_show_list() {
	/* Before disabling the option to show/hide the notelist, set it to visible (its default state) */

	jQuery.ajax({
	    type: "POST",
	    url: ajaxurl,
	    data: {
			'action':'writeMetaVal',
			'postID':jQuery("#post_id").val(),
			'key': 'showNotesList',
			'value': true
		},
	    cache: false,
	    success: function(data)
		{
			showNotesList = data;
			jQuery("#notes-list").show();

			/*  Now set option to disabled */
			jQuery("#showNotesList input").attr('checked', true);
			jQuery("#showNotesList input").attr('disabled', 'disabled');
			jQuery("#showNotesList").css('color', '#AAAAAA');
		}
	});
}

/****************************   notesArray CRUD Functions *****************************************/
/***  @globalvar notesArray - contains all notes from the json data file **************************/
/***  @globalvar tracks - array with the state of the tracks (On/Off) on the timeline *************/

function sync_notes_json(notes, tracks) {
/* Takes js notes and tracks arrays and POSTs to php to be written to json file 
	* All updates to the json data file go through this method *                */

    postID = jQuery("#post_id").val();

	jQuery.ajax({
	    type: "POST",
	    url: ajaxurl,
	    data: { 
	    	'action':'sync_notes', 
	    	'postID':postID,
	    	'notes': notes,
	    	'tracks': tracks
	    },
	    dataType: "json",
	    cache: false,
	    success: function(data)
			{	
				return data;
			}
	});
}

function add_note(noteArray) {

	/* Add new note to notesArray */
	notesArray.push(noteArray);

	/* Send to php to sync with json data file */
	sync_notes_json(notesArray, tracksArray);

	/* Insert note to pop, notelist and timeline */
	insert_note(noteArray);
}

function insert_note(noteArray) {
	/* Adds note to popcorn constructor, timeline and note list */
	eventId = add_note_to_pop(noteArray); // Add note to popcorn constructor
	popIds[noteArray.note_key] = eventId; // Add to popIds array
	add_note_to_timeline(noteArray.note_key, noteArray, pop.duration()); // Add note to timeline
	arrange_timeline();  
	add_note_to_notelist(noteArray); // Add note to note_list	
	sortNoteList();
}

function delete_note(note_id){
	/* Remove item(s) from notesArray  and saves the new data to the json data file */
	var c = confirm("Estàs segur que vols eliminar aquesta nota?");

	if (c == true){
		jQuery.each(notesArray, function(idx, val) {
			if (notesArray[idx].note_key == note_id){
				notesArray.splice(idx,1);
				return false;
			}
		});

		/* sync json file with updated data */
		sync_notes_json(notesArray, tracksArray);
		remove_note_from_pop(note_id);
		sortNoteList();
	} 
}



/********************************** FRONTEND Management ****************************************/ 

function add_note_to_pop(noteArray) {
	/* Adds note to popcorn constructor */
	// console.log(noteArray);
	if (noteArray.active == 'On') { // ONLY add to popcorn constructor if the note is active
		if (noteArray.noteType == 'text') {
			icon_img = "text-icon.png";
		    pop.footnote_hdn8({
				start: noteArray.startTime,
				end: noteArray.endTime,
				text: noteArray.contents,
				user: noteArray.userDisplay,
				key: noteArray.note_key,
				pauseOnFire: noteArray.pauseOnFire,
				target: "footnotediv"	
		    });	
		} else if (noteArray.noteType == 'wiki') {
			icon_img = "link-icon.png";
		    pop.wikipedia({
				start: noteArray.startTime,
				end: noteArray.endTime,
				src: noteArray.contents[0],
				title: "Titulo del articulo, to be added",
				pauseOnFire: noteArray.pauseOnFire,
				target: "footnotediv"
		    });
		} else if (noteArray.noteType == 'image') {
			icon_img = "image-icon.png";
			title = noteArray.contents.title;
			src = noteArray.contents.url;
		
		    pop.footnote_hdn8({
				start: noteArray.startTime,
				end: noteArray.endTime,
				text: "<h4>" + title + "</h4><a href='" + src + "' target='_blank'><img src='" + src + "'/></a>",
				user: noteArray.userDisplay,
				key: noteArray.note_key,
				pauseOnFire: noteArray.pauseOnFire,
				target: "footnotediv"
		    });
		} else if (noteArray.noteType == 'link') {
			icon_img = "link-icon.png";
			title = noteArray.contents.title;
			src = noteArray.contents.url;

		    pop.footnote_hdn8({
				start: noteArray.startTime,
				end: noteArray.endTime,
				text: "<h4>" + title + "</h4><a href='" + src + "' target='_blank'>" + src + "</a>",
				user: noteArray.userDisplay,
				key: noteArray.note_key,
				pauseOnFire: noteArray.pauseOnFire,
				target: "footnotediv"	
		    });
		} else if (noteArray.noteType == 'audio') {
			icon_img = "audio-icon_grey.png";
			title = noteArray.contents.title;
			src = noteArray.contents.audio_url;

		    pop.footnote_hdn8({
				start: noteArray.startTime,
				end: noteArray.endTime,
				text: "<h4>" + title + "</h4><div style='margin-top: 10px;'><audio id='audio_" + noteArray.note_key + "' src=" + src + " preload='auto' /></div>",
				user: noteArray.userDisplay,
				key: noteArray.note_key,
				pauseOnFire: noteArray.pauseOnFire,
				target: "footnotediv"	
		    });

		    audiojs.create(jQuery("#audio_" + noteArray.note_key));
		}		

		var eventId = pop.getLastTrackEventId();

		// Has now been added to the DOM, so let's add css classes
		jQuery("#" + eventId).addClass(noteArray.positionClass);
		jQuery("#" + eventId).addClass(noteArray.noteType);

		return eventId; // return popcorn event ID 
	}		
}


function add_note_to_timeline(noteId, noteArray, duration){
	if (noteArray.active == "On" || (noteArray.active == "Off" && user_id == noteArray.userID)) {
		eventId = popIds[noteId];

		timleline_id = "tl_" + noteId;	
		tl_class = 'tl_' + noteArray.noteType;

		// Add inactive class if note is not active and use noteId for ID since it is not in the popcorn constructor
		if (noteArray.active == "Off") {
			timleline_id = "tl_" + noteId;	
			tl_class += ' inactive';
		}
		
		// CREATE HTML for note elements
		var $start_percent = (Number(noteArray.startTime) / duration) *100;	    
		var $width_percent = ((Number(noteArray.endTime) - Number(noteArray.startTime)) / duration) *100;

		// CREATE html elements
		timeline_html = "<div class='fn_timeline " + tl_class + "' id='" + 
			timleline_id + "'><div style='height:auto;'>" + 
				noteArray.userDisplay + "<hr>" + noteArray.contents[0] + "</div></div>";

		// Add note to timeline
		trackIndex = noteArray.track;
		while (noteArray.track > (jQuery(".track").length-1)){
			jQuery("#fn_timeline-wrap").append("<div class='track'></div>");
			jQuery("#seeker-stick").css('height', jQuery("#fn_timeline-wrap").outerHeight() + "px");
		}

		jQuery(jQuery(".track")[trackIndex]).prepend(timeline_html);
		// jQuery("#fn_timeline-wrap").prepend(timeline_html); 

		// SET positions and widths
		jQuery("#" + timleline_id).css('left', $start_percent + "%");
		jQuery("#" + timleline_id).css('width', $width_percent + "%");

		// Add start attribute to dom element, allows to easily get starttime in a click function (for example)
		jQuery('#'+ timleline_id).data('start', noteArray.startTime); 

		// add hover functions to timeline elements (show preview on hover)
		// addHoverFxns(); 
	}
}

function add_note_to_notelist(noteArray){
	/* ADDS a note to the notelist
		- If it is not active, it is displayed only if user owns note
		- It's order index is determined from the relative startTime and inserted before the next (if exists, otherwise appended to table)
		- If user is owner, the edit form is appended
	*/

	if (noteArray.active == "On" || (noteArray.active == "Off" && user_id == noteArray.userID)) {
		// Add to notelist if note is active or note is inactive and the current user is the owner

		/* CREATE html elements for note list element */
		note_list_id = "nl_" + noteArray.note_key;
		note_list_editform_id = "nl_edit_" + noteArray.note_key;
		tl_class = 'tl_' + noteArray.noteType;

		var contents_preview = noteArray.noteType == 'text' ? noteArray.contents : noteArray.contents.title;
		contents_preview = contents_preview.length > 15 ? contents_preview.substr(0,14) + "..." : contents_preview;

		noteslist_html = "<tr id='" + note_list_id + "' class='" + noteArray.noteType + 
		"'><td><div style='width:30px;height:100%' class='" + tl_class + "'></div></td><td>" + 
		toHHMMSS(noteArray.startTime) + "</td><td>" + noteArray.userDisplay + 
			"</td><td><div style='max-height:60px;overflow:hidden'>" + contents_preview + 
			"</div></td><td style='width:35px;'><div class='arrow-right edit_note_open' style='display:none'></div></td></tr>";
		
		if (noteArray.active == "Off") {
			jQuery("#"+note_list_id).addClass('inactive');
		}
		
		notelist_html_form_td = jQuery('<td/>').attr('colspan', '5').addClass('note-edit-td');
		edit_form = get_html_form(noteArray); 
		notelist_html_form_td.append(edit_form);
		noteslist_html_form = jQuery('<tr id="' + note_list_editform_id + '"/>').append(notelist_html_form_td);
		noteslist_html_form.hide();
		add_submit_fxn(edit_form);

		tabl = jQuery("#notes-list table");
		rows = jQuery('tbody > tr[id^=nl_]',tabl);
		jQuery("#notes-list table").append(noteslist_html);	

		// If user owns the note, add edit / delete options
		if (user_id == noteArray.userID){ 
			// jQuery("#nl_" + noteArray.note_key).append("<div onclick='delete_note(\"" + noteArray.note_key + "\");' id='delete_note' title='Delete Note'></div>");
		
			jQuery('#' + note_list_id ).after(noteslist_html_form);
			jQuery('#' + note_list_id + " .edit_note_open").show();

			jQuery('#' + note_list_id + " .edit_note_open").click(function(){
				var formID = "#nl_edit_" + jQuery(this).parent().parent().attr('id').split("nl_")[1];
				if (jQuery(formID).is(":visible")){
					jQuery(formID).css('display','none');
					jQuery(this).removeClass('arrow-down').addClass('arrow-right').removeClass('leftcorrect');
				} else {
					jQuery(formID).css('display','table-row');
					jQuery(this).removeClass('arrow-right').addClass('arrow-down').addClass('leftcorrect');
				}
			});

			// SET options' initial values
			jQuery("#nl_" + noteArray.note_key).next().find('input[value=' + noteArray.positionClass + ']').attr('checked', 'checked');
			if (noteArray.active == 'On'){
				jQuery("#nl_" + noteArray.note_key).next().find('input[name=note-active]').attr('checked', true);
			} else {
				jQuery("#nl_" + noteArray.note_key).next().find('input[name=note-active]').attr('checked', false);
			}

			if (noteArray.pauseOnFire == 'true' || noteArray.pauseOnFire == true){
				jQuery("#nl_" + noteArray.note_key).next().find('input[name=pauseOnFire]').attr('checked', true);
			}

		} 	

		// Add start attribute to dom element, allows to easily get starttime in a click function (for example)
		jQuery('#'+ note_list_id).data('start', noteArray.startTime);  

		// Add click function to go to start time of note
		jQuery('#' + note_list_id).click(function(e){
			var starttime = jQuery(jQuery(e.target).parents('tr')[0]).data('start');
			if (starttime >= 0 && starttime < pop.duration()) {
				pop.pause(starttime);
			}
		});
	}


}

function sortNoteList(){  
	/* Sort note list according to start time (which must be the second td) */
	var $tbody = jQuery("#notes-list table tbody");
	$tbody.find('tr').sort(function(a,b){ 
	    var tda = jQuery(a).find('td:eq(1)').text();
	    var tdb = jQuery(b).find('td:eq(1)').text();
	            // if a < b return 1
	    return tda > tdb ? 1 
	           // else if a > b return -1
	           : tda < tdb ? -1 
	           // else they are equal - return 0    
	           : 0;           
	}).appendTo($tbody);


	/* Put edit forms after their parent rows */
	jQuery("tr[id^=nl_edit]").each(function(idx, tr){
		var parentrow = jQuery("#nl_" + jQuery(tr).attr('id').split("nl_edit_")[1]);
		parentrow.after(tr);
	});
}


function arrange_timeline(){
	/*  Initializes the timeline */

	jQuery("#videoTimeline").css('opacity', 1); /* Invisible until loading of notes is complete */
	jQuery("#seeker-stick").css('height', jQuery("#fn_timeline-wrap").outerHeight() + "px"); /* Set seeker-stick height */

}


function autoTrackIndex(start, end, note_key){
	/* Finds first track with no conflicting notes using the start and end times: returns track_index */

	note_key = note_key || -1; // If no note_key is given none are ignored, make -1

	available_tracks = number_range(0, jQuery(".track").length - 1);
	    
	conflicts = notesArray.filter(function(obj){
	    return (obj.endTime > start) && 
	        (obj.startTime < end) && 
	        (obj.note_key != note_key);
	});

	conflict_tracks = [];
	jQuery(conflicts).each(function(){
	    conflict_tracks.push(Number(this.track));
	});

	var usable_tracks = jQuery(available_tracks).not(conflict_tracks).get();
	if (usable_tracks.length == 0) {
	    return available_tracks.length;  // RETURNS an integer for a track index one higher than is currently in the DOM
	} else {
	    return usable_tracks.slice(0).sort()[0];   
	}
}

function remove_note_from_pop(note_key) {
	/* REMOVE from Popcorn constructor */
	popId = popIds[note_key];
	if (popId != 'undefined' && popId != undefined) {pop.removeTrackEvent(popId)};

	/* REFRESH html elements */
	tl_id = "#tl_" + note_key; // Timeline div id
	nl_id = "#nl_" + note_key; // Note list tr id
	nl_edit_id = "#nl_edit_" + note_key; // Note list form tr id

	jQuery(tl_id).remove();
	jQuery(nl_edit_id).remove();
	jQuery(nl_id).remove();	

	/* rearrange timeline */
	arrange_timeline();

}


function play_note(note_id) {
	startTime = notesArray_[note_id].startTime;
	seekToNote(Number(startTime));
}

function updateNotes(notesArray) {
	js_notesArray = JSON.parse(notesArray);
}


function get_user_display_name(userID) {
		jQuery.ajax({
		    type: "POST",
		    url: ajaxurl,
		    data: {
				'action':'get_user_display_name',
				'userID': userID
			},
		    cache: false,
		    success: function(data)
			{
				return data.user_display_name;
			}
	    });		
}

function moveOnTL(startInput) {
	/* Moves timeline note object with given dom input element (time value) */
	var noteID = jQuery(startInput).attr('id').split('nl_startTime_')[1]
	var startTime = toSec(jQuery(startInput).val());
	var $start_percent = (startTime / pop.duration()) *100;	    	
	jQuery("#tl_" + noteID).css('left', $start_percent + "%");
}


function resizeTL(durationInput) {

	var noteId = jQuery(durationInput).attr('id').split('duration_')[1];  
	var duration = toSec(jQuery(durationInput).val());
	var $width_percent = (duration) / pop.duration() *100;
	jQuery("#tl_" + noteId).css('width', $width_percent + "%");
}


function note_start(popId) {
	/* Note start event listener */
	jQuery.each(popIds, function(idx, val){
		if(val==popId){
			jQuery("tr#nl_" + idx).addClass('firingRow');
		}
	});
	
}


function note_end(popId) {
	/* Note end event listener */
	jQuery.each(popIds, function(idx, val){
		if(val==popId){
			jQuery("tr#nl_" + idx).removeClass('firingRow');
		};
	});
}


/******** DEPRECATED ************************************************/
function updateNotesArray(noteVals) { 
	/* Adds/Updates note in notesArray */
    var keysArray = ['key', 'userID', 'userDisplay', 'startTime', 'endTime', 'note_type', 'positionClass', 'active',  'pauseOnFire', 'contents'];
    var noteVals_assoc = Array();
    for (var q=0;q<keysArray.length-1;q++) { // CUT it one short, contents handle as own embedded array (+7)
		noteVals_assoc[keysArray[q]] = noteVals[q]; 
    }
    noteVals_assoc['contents'] = noteVals.slice(keysArray.length-1);
    notesArray_[noteVals_assoc['key']] = noteVals_assoc;		

    return noteVals_assoc;
}

function sortNotesArray(_notesArray) {
	/* Sorts notesArray by the startTime of the events */
	orderArray = Array();
	for (note in _notesArray){
	    orderArray.push(_notesArray[note]);
	}

	orderArray.sort(function(a,b){
	    return Number(a.startTime) < Number(b.startTime) ? -1 : (Number(a.startTime) > Number(b.startTime) ? 1 : 0);
	});
	
	return orderArray;
}

function getOrderIndex(key) {
	/* Use notesArray to determine index sorted by startTime, returns Index (starting with 0) */

	orderArray = sortNotesArray(notesArray);

	// orderArray.sort(function(a,b){
	//     return Number(a.startTime) < Number(b.startTime) ? -1 : (Number(a.startTime) > Number(b.startTime) ? 1 : 0);
	// });

	return findWithAttr(orderArray, 'note_key', String(key));
}
/**********************************************************************/




////////////////////////  FORM JS //////////////////////////////////////////////

function toggle_form(e){ // NOTES FORM TABS TOGGLE FXN
	/* Tabs for displaying different form elements */

	/* Hide all type-specific form fields */
	var ids = ['text_form', 'image_form', 'link_form', 'audio_form'];
	for (var i=0;i<ids.length;i++){
		jQuery("#"+ids[i]).hide();
	}

	/* Show selected form fields */
	var idkey = jQuery(e.target).attr('id').split("selector_")[1]; // GET key to search for matching ids from selector (this) id
	jQuery("#" + idkey + "_form").show();


	// $("#add_footnote_form > [id*=" + idkey + "]").css('display', 'block');// FILTER child elements containing matching id key, and show
}

function initialize_footnote_form() {

	jQuery.ajaxSetup({      // CLEAR function for ajax calls, not sure if it's necessary
		   cache: false,    
		   data : null      
	 });

	var durinput = jQuery("#add_footnote_form input[name=note_duration]");

	jQuery("#add_footnote_form input[name=note_start]").mask('00:00:00');
	jQuery("#add_footnote_form input[name=note_start]").val(toHHMMSS(0));
	durinput.mask('00:00:00');
	durinput.val(toHHMMSS(5));
 
	// ADD spinner to duration field

	var spinner_up = jQuery('<div class="arrow-up spinner"/>');
	durinput.after(spinner_up);
	spinner_up.click(function(e){
		var durinput = jQuery(jQuery(e.target)[0]).siblings('input[name=note_duration]');
		durinput.val(toHHMMSS(toSec(durinput.val()) + 1));
	});

	var spinner_down = jQuery('<div class="arrow-down spinner"/>');
	durinput.after(spinner_down);
	spinner_down.click(function(e){
		var durinput = jQuery(jQuery(e.target)[0]).siblings('input[name=note_duration]');
		if ((toSec(durinput.val()) - 1) >= 0) {
			durinput.val(toHHMMSS(toSec(durinput.val()) - 1));
		}
	});

    //// AJAX add note fxn //////////////////////////////////////////
    jQuery("#add_footnote_form").submit(function(e){
		/* CALLS ajax function to create new text note */
		e.preventDefault();

		// GET start time
		// var start = pop.currentTime();
		var video_duration = pop.duration();
		var userID = user_id;
		var userDisplay = user_display;

		// Grab contents depending on chosen type, and validate fields
		if (userDisplay == '' || !userDisplay) {
			alert("No estás autenticado!");
			return false;
		}

		// Determine current form by visibility, get contents
		var noteType = '';
		var contents = '';

		var form = jQuery("#add_footnote_form");

		if (jQuery("#text_text").is(":visible")) {
			// var form = jQuery("#text_form");
			var noteType = 'text';
			var contents = jQuery(form).find("textarea[name=text_text]").val().replace('[,]', ',');
		} else if (jQuery("#wikipedia_url").is(":visible")) { // NOT IN USE
			var form = jQuery("#wiki_form");
			var noteType = 'wiki';
		} else if (jQuery("#image_form").is(":visible")) {
			var form = jQuery("#image_form");
			var noteType = 'image';
			var image_title = jQuery(form).find("input[name=image_title]");
			var image_url = jQuery(form).find("input[name=image_url]");
			var contents = {"title":jQuery(image_title).val(),"url":jQuery(image_url).val()};
		} else if (jQuery("#link_form").is(":visible")) {
			var form = jQuery("#link_form");
			var noteType = 'link';
			var contents = {"title":jQuery(form).find("input[name=link_title]").val(),"url":jQuery(form).find("input[name=link_url]").val()};
		} else if (jQuery("#audio_form").is(":visible")) {
			var form = jQuery("#audio_form");
			var noteType = 'audio';
			var contents = {"title":jQuery(form).find("input[name=audio_title]").val(),"audio_url":jQuery(form).find("input[name=audio_url]").val()};
		}

		errors = validateNoteForm(form);

		if (errors.length > 0){
			alert(errors.join('\n'));
			return false; 
		};

		// Grab options
		var position = jQuery("#add_footnote_form input[name=position-corners]:checked").val();
		var active = (jQuery("#add_footnote_form").find("input[name='note-active']").is(":checked") ? 'On':'Off');
		var pauseOnFire = jQuery("#add_footnote_form").find("input[name='pauseOnFire']").is(":checked");
		
		var start = toSec(jQuery("#add_footnote_form input[name='note_start']").val());
		var note_duration = Math.round(Number(toSec(jQuery("#add_footnote_form input[name='note_duration']").val())) * 100) / 100; 
		var end = Math.round(start * 100)/100 + note_duration;
		var note_key = generate_note_key();
		var track = autoTrackIndex(start, end);

		if (!position) {position = "topRight"};


		/* Build note array  */

	    var noteArray = {
	    	"note_key":note_key,
	    	"userID":userID,
	    	"userDisplay":userDisplay,
	    	"noteType":noteType,
	    	"startTime":start,
	    	"endTime":end,
	    	"positionClass":position,
	    	"active":active,
	    	"pauseOnFire":pauseOnFire,
	    	"contents":contents,
	    	"track":track
	    };


		/* Add note to notesArray and save via method */
		add_note(noteArray);

		/* Clear and reset form fields */
		jQuery("#add_footnote_form textarea").val("");
		jQuery("#add_footnote_form input[type=text").val("");
		jQuery("#add_footnote_form input[name=note_start]").val(toHHMMSS(pop.currentTime()));
		jQuery("#add_footnote_form input[name=note_duration]").val(toHHMMSS(5));
		jQuery("#add_footnote_form").find("input[name='note-active']").attr("checked", true);
		jQuery("#add_footnote_form").find("input[name=pauseOnFire]").attr("checked", false);
		jQuery("#add_footnote_form input[name=position-corners]").val("topLeft");

    });
}

function validateNoteForm(form) {
	var errors = new Array();

	if (jQuery(form).find("textarea[name=text_text]").length > 0) {//TEXT

		var textarea = jQuery(form).find("textarea[name=text_text]");
		jQuery(textarea).css('border-color', '');
		
		if (jQuery(textarea).val() == '') { // Text area cannot be empty
			jQuery(textarea).css('border-color', 'red');
			errors.push('Olvidaste introducir texto?');
		} 		
	} else if (jQuery(form).find("input[name=image_title]").length > 0) {//IMAGE

		var image_title = jQuery(form).find("input[name=image_title]");
		var image_url = jQuery(form).find("input[name=image_url]");
		
		jQuery(image_url).css('border-color', '');
		jQuery(image_title).css('border-color', '');	
		
		var contents = jQuery(image_title).val().replace('[,]', ',') + "[,]" + jQuery(image_url).val().replace('[,]', ',');

		if (jQuery(image_title).val() == '') {
			jQuery(image_title).css('border-color', 'red');
			errors.push('Pon un título para esta imágen.');
		};
		if (jQuery(image_url).val() == '') {
			jQuery(image_url).css('border-color', 'red');
			errors.push('Falta url de la imágen.');
		};
		if (!jQuery(image_url).val().match(/.jpg$/) && !jQuery(image_url).val().match(/.png$/) && !jQuery(image_url).val().match(/.gif$/) && !jQuery(image_url).val().match(/.tif$/)) {
			jQuery(image_url).css('border-color', 'red');
			errors.push('La imágen tiene que ser de tipo jpg, gif, png o tif.');
		};

	} else if (jQuery(form).find('input[name=link_title]').length > 0) {//LINK 
		jQuery(form).find('input[name=link_title]').css('border-color', '');
		jQuery(form).find('input[name=link_url]').css('border-color', '');
		
		if (jQuery(form).find("input[name=link_title]").val() == '') {
			jQuery(form).find("input[name=link_title]").css('border-color', 'red');
			errors.push('Falta un título.');				
		}; 
		if (jQuery(form).find("input[name=link_url]").val() == '') {
			jQuery(form).find("input[name=link_url]").css('border-color', 'red');
			errors.push('Falta la url.');				
		}; 
	} else if (jQuery(form).find('input[name=audio_title]').length > 0) {//AUDIO
		jQuery(form).find('input[name=audio_title]').css('border-color', '');
		jQuery(form).find('input[name=audio_url]').css('border-color', '');
		
		if (jQuery(form).find("input[name=audio_title]").val() == '') {
			jQuery(form).find("input[name=audio_title]").css('border-color', 'red');
			errors.push('Falta un título.');				
		}; 
		if (jQuery(form).find("input[name=audio_url]").val() == '') {
			jQuery(form).find("input[name=audio_url]").css('border-color', 'red');
			errors.push('Falta la url.');				
		};
		if (!jQuery(form).find("input[name=audio_url]").val().match(/.mp3$/)) {
			jQuery(form).find("input[name=audio_url]").css('border-color', 'red');
			errors.push('El audio tiene que ser de tipo mp3.');
		};		
	}

	// Validate options
	var dur_val = jQuery(form).find("input[name=note_duration]").val();
	jQuery(form).find("input[name=note_duration]").css('border-color', '');

	if (dur_val == '' || dur_val == '0') {
		jQuery(form).find("input[name=note_duration]").css('border-color', 'red');
		errors.push('El valor de la duración no está válida');
	} 

	return errors;
}

function get_html_form(noteArray) {
	/* Creates html QUICK EDIT form for a given note type */

	var form = jQuery('<form method="POST" action="#"></form>');
	form.addClass('edit_note_' + noteArray.noteType);
	fields = [];


	/*****************  Add type-specific fields ******************/
	if (noteArray.noteType == 'text') {
		textarea = jQuery('<textarea/>');
		textarea.attr('name', 'text_text');
		textarea.attr('cols', '45');
		textarea.attr('rows', '5');
		textarea.val(noteArray.contents);
		fields.push(textarea);

	} else if (noteArray.noteType == 'image') {
		title = jQuery('<input/>');
		title.attr('name', 'image_title');
		title.attr('type', 'text');
		title.val(noteArray.contents.title);
		fields.push(title);
		
		url = jQuery('<input/>');
		url.attr('name', 'image_url');
		url.attr('type', 'text');
		url.val(noteArray.contents.url);
		fields.push(url);

	} else if (noteArray.noteType == 'link') {
		title = jQuery('<input/>');
		title.attr('name', 'link_title');
		title.attr('type', 'text');
		title.val(noteArray.contents.title);
		fields.push(title);

		url = jQuery('<input/>');
		url.attr('name', 'link_url');
		url.attr('type', 'text');
		url.val(noteArray.contents.url);
		fields.push(url);
	} else if (noteArray.noteType == 'audio') {
		title = jQuery('<input/>');
		title.attr('name', 'audio_title');
		title.attr('type', 'text');
		title.val(noteArray.contents.title);
		fields.push(title);

		url = jQuery('<input/>');
		url.attr('name', 'audio_url');
		url.attr('type', 'text');
		url.val(noteArray.contents.audio_url);
		fields.push(url);
	}

	fields.push('<input type="hidden" name="note_key" value="' + noteArray.note_key + '"/>');
	for (var i=0;i<fields.length;i++) {
		form.append(fields[i]);
	}


	/**************  Add option fields *******************************/
	// encapsulate with <div class="note_options"/>
	var note_options = jQuery("<div class='note_options'/>");

	/****** Time Fields ***************/
	////////////STRUCTURE///////////////
	// <div id="time_fields">
	// 	<label for="note_start">Inicio</label><br>
	// 	<input type="text" name="note_start" readonly="">
	// 	<br>
	// 	<label for="note_duration">Duración</label><br>
	// 	<input type="text" name="note_duration" id="note_duration"><br>
	// 	<label><input type="checkbox" name="pauseOnFire">&nbsp;Parar vídeo</label>
	// </div>

	var time_fields = jQuery('<div class="time_fields"/>');

	// jQuery("#add_footnote_form input[name=note_start]").mask('00:00:00');
	// jQuery("#add_footnote_form input[name=note_start]").val(toHHMMSS(0));
	// jQuery("#add_footnote_form input[name=note_duration]").mask('00:00:00');
	// jQuery("#add_footnote_form input[name=note_duration]").val(toHHMMSS(5));

	// ADD spinner to start and duration fields
	var max_startTime = Math.floor(pop.duration());

	/** Start Input *****/
	var start_label = jQuery('<label for="startTime">Inici</label><br>');
	var start = jQuery('<input type="text" name="startTime" id="nl_startTime_' + 
		noteArray.note_key + '"/>').mask('00:00:00');
	start.val(toHHMMSS((Math.round(noteArray.startTime*100)/100)));

	var startfield = jQuery("<div/>");
	var start_spinner_up = jQuery('<div class="arrow-up spinner"/>');
	var start_spinner_down = jQuery('<div class="arrow-down spinner"/>');
	startfield.append(start_label, start, start_spinner_up, start_spinner_down)
	start_spinner_up.click(function(e){
		var start_input = jQuery(jQuery(e.target)[0]).siblings('input[name=startTime]');
		if ((toSec(start_input.val())+1) < max_startTime) {
			start_input.val(toHHMMSS(toSec(start_input.val()) + 1));
			moveOnTL(start_input);
		}
	});

	start_spinner_down.click(function(e){
		var start_input = jQuery(jQuery(e.target)[0]).siblings('input[name=startTime]');
		if ((toSec(start_input.val()) - 1) >= 0) {
			start_input.val(toHHMMSS(toSec(start_input.val()) - 1));
			moveOnTL(start_input);
		}
	});


	/** Duration Input ****/
	var note_duration = Math.round(noteArray.endTime*100)/100 - Math.round(noteArray.startTime*100)/100;
	var duration_label = jQuery('<br><label for="note_duration">Durada</label><br>');
	var duration = jQuery('<input type="text" name="note_duration" id="duration_' + noteArray.note_key + 
		'" name="note_duration"/>').mask('00:00:00');
	duration.val(toHHMMSS(note_duration));

	var duration_field = jQuery("<div/>");
	var dur_spinner_up = jQuery('<div class="arrow-up spinner"/>');
	var dur_spinner_down = jQuery('<div class="arrow-down spinner"/>');

	duration_field.append(duration_label, duration, dur_spinner_up, dur_spinner_down);

	dur_spinner_up.click(function(e){
		var duration_input = jQuery(jQuery(e.target)[0]).siblings('input[name=note_duration]');
		duration_input.val(toHHMMSS(toSec(duration_input.val()) + 1));
		resizeTL(duration_input);
	});

	dur_spinner_down.click(function(e){
		var duration_input = jQuery(jQuery(e.target)[0]).siblings('input[name=note_duration]');
		if (toSec(duration_input.val()) -1 > 0) {
			duration_input.val(toHHMMSS(toSec(duration_input.val()) - 1));
			resizeTL(duration_input);
		}
	});


	time_fields.append(startfield, duration_field);
	time_fields.append(get_pauseOnFire());
	note_options.append(time_fields);


	note_options.append(get_position_selector());
	note_options.append(get_active_switch(noteArray.note_key));	
	
	form.append(note_options);

	var actions = jQuery('<div style="margin:0px auto;width:90%"/>')

	var delete_button = jQuery("<input type='button' onclick='delete_note(\"" + 
		noteArray.note_key + "\");' class='delete_note' title='Eliminar' value='Eliminar' style='width:45%;margin-right:0px'>")
	submit = jQuery('<input type="submit" style="width:45%;margin-left:0px"/>');
	submit.attr('value', 'Actualitzar');
	actions.append(submit, delete_button);
	form.append('<hr>', actions);

	var form_wrapper = jQuery('<div class="notelist_form_wrapper"/>');
	form_wrapper.append(form);
	return form_wrapper;
}


function add_submit_fxn(form) {

	/* Activate click action on Active Switch */
	jQuery(form).children('.Switch').click(function() {
		jQuery(this).toggleClass('On').toggleClass('Off');
	});
	
	/* Add submit method to form */
    jQuery(form).submit(function(e){
		/* CALLS ajax function to create new text note */
		e.preventDefault();

		// CREATE post vars
		var start = toSec(jQuery(form).find('input[name=startTime]').val());

		var note_duration = Math.round(Number(toSec(jQuery(form).find('input[name=note_duration]').val())) * 100) / 100; 
		var end = Math.round(start * 100)/100 + note_duration;
		var video_duration = pop.duration();
		var userID = user_id;
		var userDisplay = user_display;
		var position = jQuery(form).find("input[name='position-corners']:checked").val();
		var pauseOnFire = jQuery(form).find("input[name='pauseOnFire']").is(":checked"); 
		var note_key = jQuery(form).find('input[name=note_key]').val();
		var active = (jQuery(form).find("input[name='note-active']").is(":checked") ? 'On':'Off');

		var noteType = jQuery(jQuery(form).find('form')).attr('class').split('edit_note_')[1];
		var track = autoTrackIndex(start, end, note_key); 

		// Grab contents depending on chosen type
		if (noteType == 'text') {
			var contents = jQuery(form).find("textarea[name=text_text]").val();
		} else if (noteType == 'image') {
			var image_title = jQuery(form).find("input[name=image_title]");
			var image_url = jQuery(form).find("input[name=image_url]");
			var contents = {"title":jQuery(image_title).val(),"url":jQuery(image_url).val()};
		} else if (noteType == 'link') {
			var contents = {"title":jQuery(form).find("input[name=link_title]").val(),"url":jQuery(form).find("input[name=link_url]").val()};			
		} else if (noteType == 'wiki') { // NOT IN USE //
			var contents = jQuery(form).find('input[name=wiki_title]')[0].value + "[,]" + jQuery(form).find('input[name=wiki_url]')[0].value;
		} else if (noteType == 'audio') {
			var contents = {"title":jQuery(form).find("input[name=audio_title]").val(),"audio_url":jQuery(form).find("input[name=audio_url]").val()};
		}

		errors = validateNoteForm(form);
		if (errors.length > 0){
			return false;
		};

		/* Remove item from notesArray */         
		notesArray = jQuery.grep(notesArray, function(e){return e.note_key!=note_key});

		/* Remove item from popcorn constructor */
		remove_note_from_pop(note_key);

		/* Build note array to insert */
	    var noteArray = {
	    	"note_key":note_key,
	    	"userID":userID,
	    	"userDisplay":userDisplay,
	    	"noteType":noteType,
	    	"startTime":start,
	    	"endTime":end,
	    	"positionClass":position,
	    	"active":active,
	    	"pauseOnFire":pauseOnFire,
	    	"contents":contents,
	    	"track":track
	    };

	    /* Insert note in FE elements */
	    add_note(noteArray)
	    
    });
}


function get_active_switch(noteID) {
/* Generates html for active button 
*  with the following html structure:
*   
	<div id="note_active_switch">
		<div class="onoffswitch">
		    <input type="checkbox" name="note-active" class="onoffswitch-checkbox" id="myonoffswitch" checked>
		    <label class="onoffswitch-label" for="myonoffswitch">
		        <div class="onoffswitch-inner"></div>
		        <div class="onoffswitch-switch"></div>
		    </label>
		</div>							
	</div>
*
*/

	var container1 = jQuery('<div class="note_active_switch">');
	var container2 = jQuery('<div class="onoffswitch"/>')
	var input = jQuery('<input type="checkbox" name="note-active" class="onoffswitch-checkbox" id="edit_note_activeswitch_' + noteID + '" checked>');
	var label = jQuery('<label class="onoffswitch-label" for="edit_note_activeswitch_' + noteID + '">');
	var inner = jQuery('<div class="onoffswitch-inner"></div>');
	var toggle = jQuery('<div class="onoffswitch-switch"></div>');

	label.append(inner, toggle);
	container2.append(input, label);
	container1.append(container2);

	return container1;
}


function get_position_selector() {
	/* Generates html for position selector */
	position_selector = jQuery('<div class="position_selector"></div>');
	position_selector.append('<label for="position-corners">Posici&oacute;</label>');
	pos_formwrapper = jQuery('<div class="position_form-wrapper"></div>');
	input1 = jQuery('<input></input>');
	input2 = jQuery('<input></input>');
	input3 = jQuery('<input></input>');
	input4 = jQuery('<input></input>');
	positions = ['topLeft', 'topRight', 'bottomRight', 'bottomLeft'];
	inputs = [input1, input2, input3, input4];
	for (var i=0;i<4;i++) {
	    inputs[i].attr('type','radio');
	    inputs[i].attr('name','position-corners');
	    inputs[i].attr('value', positions[i]);
	    inputs[i].addClass(positions[i]);
	    label = jQuery('<label></label>');
	    label.attr('for', positions[i]);
	    label.addClass(positions[i]);
	    pos_formwrapper.append(inputs[i]);
	    pos_formwrapper.append(label);
	}

	if (viewMode == "A") {jQuery(position_selector).hide()}; 

	position_selector.append(pos_formwrapper);

	return position_selector;
}

function get_pauseOnFire() {
	/* Generates HTML for pauseOnFire checkbox */

	wrapper = jQuery('<div class="pauseOnFire"></div>');
	label = jQuery('<label/>').html('Pausa vídeo');
	label.append('<input type="checkbox" name="pauseOnFire"/>');
	wrapper.append(label);

	return wrapper;
}

//////////////////////////// Init Popcorn and Player controls /////////////////////////////////////
function init_popcorn() {
	jQuery("#debug_info").append("<div>calling init_popcorn()</div>")
	pop = Popcorn("#video");

    pop.on("timeupdate", function(e) {
	    videoTimeUpdateHandler(e);
    });
     
	pop.on("ended", function(e) {
		playToggle();
		
		// Update the button text to 'Play'
		jQuery("#play-pause").addClass("play");
		jQuery("#play-pause").removeClass("pause");	  		
		
		pop.play(0);
		pop.pause();
	});
	
	////////////////// VIDEO CONTROLS ////////////////////
    var playButton = document.getElementById("play-pause-wrapper");
    
    playButton.addEventListener("click", function() {
		playToggle();
    });

    /////////////////// SCRUBBER ///////////////////////
    var $scrubber = jQuery("#scrubber");
    var $progress = jQuery("#progress");
    // width_corr = (jQuery("#scrubber").width() - jQuery("#seeker").width())/jQuery("#scrubber").width();

    $scrubber.click(function(e){
    	scrubberMouseDownHandler(e);
    });


    jQuery("#seeker").draggable({
		drag: function(e) {
		    scrubbing = true;
		    paused = pop.paused();
		    if (!paused){pop.pause()};
		    percent = (jQuery(this).offset().left - jQuery("#scrubber").offset().left) / (jQuery("#scrubber").width() - jQuery(this).outerWidth());
		     
		    if (percent < 1) {
		    	seekVideo(percent);
		    } 	    
		},
		  stop: function(e) {
		    scrubbing = false;
		    percent = (e.pageX - jQuery("#scrubber").offset().left) / jQuery("#scrubber").width();
		    //playToggle();
		
		},
		axis:'x',
		containment: "parent"
    });

    function updateBuffer(){
		jQuery("#buffer").css('width', pop.buffered().length*100 + "%");
    }
       

    var buffer_load = setInterval(function(){
	    if (pop.buffered().length < 1) {
		updateBuffer();
	    } else if (pop.buffered().length == 1) {
		updateBuffer();
		//clearInterval(buffer_load);
	    }
	}, 500);

	(function($) {
	    $("#add_footnote").find('li').each(function(){
		    $(this).click(function(){
				$(this).addClass('active_li').siblings().removeClass('active_li');
		    });
		});
	})(jQuery);
}

//////////////////////////// VIDEO PLAYER JS ///////////////////////////////////////////////////////

function playToggle() {

	if (paused == true) {
	  // Play the video
	  video.play();

	  // Update the button text to 'Pause'
	  jQuery("#play-pause").html('pause');
	  jQuery("#play-icon").hide();
	  jQuery("#pause-icon").show();
	} else {
	  // Pause the video
	  video.pause();

	  // Update the button text to 'Play'
	  jQuery("#play-pause").html('play');
	  jQuery("#play-icon").show();
	  jQuery("#pause-icon").hide();
	}
	paused = pop.paused();
}

function pausePop(){
	if (paused == false) {
		// Pause the video
		video.pause();

		// Update the button text to 'Play'
		jQuery("#play-pause").html('play');
		jQuery("#play-icon").show();
		jQuery("#pause-icon").hide();

		paused = pop.paused();
	}
}

    
function seekToNote(time) {
    pop.play(time+0.1);
    // pop.pause();
}
      
/////////// Scrubber functions ////////////////
    
function scrubberMouseDownHandler(e) {
	var $this = jQuery("#scrubber");
	var x = e.pageX - $this.offset().left;
	var percent = x / $this.width();
	    
	updateProgressWidth(percent);
	updateVideoTime(percent);
}
    
function updateVideoTime(percent) {
	pop.play(percent * pop.duration());

	if (paused){pop.pause()};
	fn_time = Math.round(pop.currentTime() * 100) / 100; 
	jQuery('#fn_start').text(fn_time); 
}
      
function updateProgressWidth(percent) {
	jQuery("#progress").width((percent * 100) + "%");
	var width_corr = (jQuery("#scrubber").width() - jQuery("#seeker").width())/jQuery("#scrubber").width();
	if (!scrubbing) {
	    jQuery("#seeker").css('left', (percent * width_corr *100 + "%"));	    
	}

	jQuery("#input_text").val(percent);	
}
    
function videoTimeUpdateHandler(e) { // HANDLES timeupdates /////////

	jQuery("#progress-time").html(toHHMMSS(pop.currentTime()));
	jQuery("#add_footnote_form input[name=note_start]").val(toHHMMSS(pop.currentTime()));

	var percent = pop.currentTime() / pop.duration();
	updateProgressWidth(percent);
}
    
function seekVideo(e) {
	updateVideoTime(e);
}
   
function addHoverFxns(){
	//add hover functions to timeline, show/hide the preview
	jQuery(".fn_timeline").hover(function(){jQuery(this).find('div').show();},function(){jQuery(this).find('div').hide()}); 
}

//////////// UTILS /////////////////////////////////
// GENERAL JS UTILITY FUNCTIONS ////////////////////

function generate_note_key() {
	/* Generates UUID to for use as the note_key */
    // http://www.ietf.org/rfc/rfc4122.txt
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
}

function noteByID(noteID) {
	/* Retrieves note from notesArray using given noteID */
	for (var i=0;i<notesArray.length;i++){
		if (notesArray[i].note_key == noteID) {
			return notesArray[i];
		}
	}
}


/* Generates unique id (counter) */
var uid = (function(){var id=0;return function(){if(arguments[0]===0)id=0;return id++;}})();

/* Loops array to find index of obj with given attribute and value */
function findWithAttr(array, attr, value) {
    for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return i;
        }
    }
}

function number_range(beginning, end) {
    var numbers = [];
    for (; beginning <= end; beginning++) {
        numbers[numbers.length] = beginning; 
    }
    return numbers;
}

function toMinSec(time){
	date = new Date(time*1000);
	mm = date.getMinutes();
	ss = date.getSeconds();
	if (mm < 10) {mm = "0"+mm;}
	if (ss < 10) {ss = "0"+ss;}
	return mm + ":" + ss;
}

function toHHMMSS(secs)
{
    var t = new Date(1970,0,1);
    t.setSeconds(secs);
    var s = t.toTimeString().substr(0,8);
    if(secs > 86399)
    	s = Math.floor((t - Date.parse("1/1/70")) / 3600000) + s.substr(2);
    return s;
}

function toSec(hhmmss) {
	// hhmmss format -- HH:MM:SS, returns total seconds
	var hms = hhmmss;   
	var a = hms.split(':'); 
	// minutes are worth 60 seconds. Hours are worth 60 minutes.
	var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
	return seconds;
}

function MinSectoSec(time) {
	var hms = time;   
	var a = hms.split(':'); 
	// minutes are worth 60 seconds. Hours are worth 60 minutes.
	var seconds = (+a[0]) * 60 + (+a[1]); 
	return seconds;
}

    
