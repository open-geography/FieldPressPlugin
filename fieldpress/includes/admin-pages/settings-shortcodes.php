<div id="poststuff" class="metabox-holder m-settings cp-shortcode-box cp-wrap">
	<form action='' method='post'>

		<div class="postbox">
			<h3 class='hndle cp-shortcode-heading'><span><?php _e( 'Shortcodes', 'cp' ); ?></span></h3>

			<div class="inside">
				<p><?php _e( 'Shortcodes allow you to include dynamic content in posts and pages on your site. Simply type or paste them into your post or page content where you would like them to appear. Optional attributes can be added in a format like <em>[shortcode attr1="value" attr2="value"]</em>. ', 'cp' ); ?></p>
				<table class="form-table">
					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Instructors List', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_instructors]</span><br/>
							<span class=""><?php _e( 'Display a list or count of Instructors ( gravatar, name and link to profile page )', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span>
									– <?php _e( 'ID of the field trip instructors are assign to ( required if use it outside of a loop )', 'cp' ); ?>
								</li>
								<li><span>style</span>
									– <?php _e( 'How to display the instructors. Options: <em>block</em> (default), <em>list</em>, <em>list-flat</em>, <em>count</em> (counts instructors for the field trip).', 'cp' ); ?>
								</li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_plural</span>
									– <?php _e( 'Plural if more than one instructor. Default: Instructors', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to put after label. Default is colon (<strong>:</strong>)', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag to wrap the label (without brackets, e.g. <em>h3</em>). Default: empty', 'cp' ); ?>
								</li>
								<li><span>link_text</span>
									– <?php _e( 'Text to click to link to full profiles. Default: "View Full Profile".', 'cp' ); ?>
								</li>
								<li><span>show_label</span>
									– <?php _e( 'Show the label. Options: <em>yes</em>, <em>no</em>.', 'cp' ); ?></li>
								<li><span>summary_length</span>
									– <?php _e( 'Length of instructor bio to show when style is "blocl". Default: 50', 'cp' ); ?>
								</li>
								<li><span>list_separator</span>
									– <?php _e( 'Symbol to use to separate instructors when styl is "list" or "list-flat". Default: comma (,)', 'cp' ); ?>
								</li>
								<li><span>avatar_size</span>
									– <?php _e( 'Pixel size of the avatars when viewing in block mode. Default: 80', 'cp' ); ?>
								</li>
								<li><span>default_avatar</span>
									– <?php _e( 'URL to a default image if the user avatar cannot be found.', 'cp' ); ?>
								</li>
								<li><span>show_divider</span>
									– <?php _e( 'Put a divider between instructor profiles when style is "block".', 'cp' ); ?>
								</li>
								<li><span>link_all</span>
									– <?php _e( 'Make the entire instructor profile a link to the full profile.', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes to use for further styling.', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_instructors]<br/>[field_instructors field_id="5"]<br/>[field_instructors
								style="list"]</code>
							<span class="description"></span>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Instructor Avatar', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_instructor_avatar]</span><br/>
							<span class=""><?php _e( 'Display an instructor’s avatar.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>instructor_id</span> – <?php _e( 'The user id of the instructor.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>thumb_size</span>
									– <?php _e( 'Size of avatar thumbnail. Default: 80', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'CSS class to use for the avatar. Plugin Default: small-circle-profile-image', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_instructor_avatar instructor_id="1"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Instructor Profile URL', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[instructor_profile_url]</span><br/>
							<span class=""><?php _e( 'Returns the URL to the instructor profile.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>instructor_id</span> – <?php _e( 'The user id of the instructor.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[instructor_profile_url instructor_id="1"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Details', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field]</span><br/>
							<span class=""><?php _e( 'This shortcode allows you to display details about your field trip. <br /><strong>Note:</strong> All the same information can be retrieved by using the specific field shortcodes following.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
								<li>
									<span>show</span>
									– <?php _e( 'All the fields you would like to show. Default: summary', 'cp' ); ?>
									<p class="description"><strong><?php _e( 'Available fields:', 'cp' ) ?></strong>
										title, summary, description, start, end, dates, enrollment_start,
										enrollment_end, enrollment_dates, enrollment_type, class_size, cost, language,
										instructors, image, video, media, button, action_links, calendar</p>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>show_title</span>
									– <?php _e( 'yes | no - Required when showing the "title" field.', 'cp' ); ?></li>
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field show="title,summary,cost,button" field_id="5"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Title', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_title]</span><br/>
							<span class=""><?php _e( 'Displays the field trip title.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>title_tag</span>
									– <?php _e( 'The HTML tag (without brackets) to use for the title. Default: h3', 'cp' ); ?>
								</li>
								<li><span>link</span>
									– <?php _e( 'Should the title link to the field trip?  Accepts "yes" or "no". Default: no', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_title field_id="4"]<br/>[field_title]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Summary', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_summary]</span><br/>
							<span class=""><?php _e( 'Displays the field trip summary/excerpt.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>length</span>
									– <?php _e( 'Text length of the summary. Default: empty (uses WordPress excerpt length)', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_summary field_id="4"]<br/>[field_summary]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Description', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_description]</span><br/>
							<span class=""><?php _e( 'Displays the longer Field Trip Description (post content).', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_description field_id="4"]<br/>[field_description]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Start Date', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_start]</span><br/>
							<span class=""><?php _e( 'Shows the field trip start date.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_start]<br/>[field_start label="Awesomeness begins on" label_tag="h3"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip End Date', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_end]</span><br/>
							<span class=""><?php _e( 'Shows the field trip end date.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_date_text</span>
									– <?php _e( 'Text to display if the field trip has no end date. Default: No End Date', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_end]<br/>[field_end label="The End." label_tag="h3" field_id="5"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Date and Time', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_dates]</span><br/>
							<span class=""><?php _e( 'Displays the field trip start and end date range. Typically as [field_start] - [field_end].', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_date_text</span>
									– <?php _e( 'Text to display if the field trip has no end date. Default: No End Date', 'cp' ); ?>
								</li>
								<li><span>alt_display_text</span>
									– <?php _e( 'Alternate display when there is no end date. Default: Open-ended', 'cp' ); ?>
								</li>
								<li><span>show_alt_display</span>
									– <?php _e( 'If set to "yes" use the alt_display_text. If set to "no" use the "no_date_text". Default: no', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_dates field_id="42"]<br/>[field_dates field_id="42" show_alt_display="yes"
								alt_display_text="Learn Anytime!"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Enrollment Start', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_enrollment_start]</span><br/>
							<span class=""><?php _e( 'Displays the field trip enrollment start date.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_date_text</span>
									– <?php _e( 'Text to display if the field trip has no defined enrollment start date. Default: Enroll Anytime', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_enrollment_start]<br/>[field_enrollment_start label="Signup from"
								label_tag="em"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Enrollment End', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_enrollment_end]</span><br/>
							<span class=""><?php _e( 'Shows the field trip enrollment end date.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_date_text</span>
									– <?php _e( 'Text to display if there is no enrollment end date. Default: Enroll Anytime', 'cp' ); ?>
								</li>
								<li><span>show_all_dates</span>
									– <?php _e( 'If "yes" it will display the no_date_text even if there is no date. If "no" then nothing will be displayed. Default: no', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_end]<br/>[field_end label="End" label_delimeter="-"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Enrollment Dates', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_enrollment_dates]</span><br/>
							<span class=""><?php _e( 'Displays the field trip enrollment start and end date range. Typically as [field_enrollment_start] - [field_enrollment_end].', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>date_format</span>
									– <?php _e( 'PHP style date format. Default: WordPress setting.', 'cp' ); ?></li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_date_text</span>
									– <?php _e( 'Text to display if there is no enrollment start or end dates. Default: Enroll Anytime', 'cp' ); ?>
								</li>
								<li><span>alt_display_text</span>
									– <?php _e( 'Alternate display when there is no enrollment start or end dates. Default: Open-ended', 'cp' ); ?>
								</li>
								<li><span>show_alt_display</span>
									– <?php _e( 'If set to "yes" use the alt_display_text. If set to "no" use the "no_date_text". Default: no', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_enrollment_dates]<br/>[field_enrollment_dates no_date_text="No better time
								than now!"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Coure Enrollment Type', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_enrollment_type]</span><br/>
							<span class=""><?php _e( 'Shows the type of enrollment (manual, prerequisite, passcode or anyone).', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>manual_text</span>
									– <?php _e( 'Text to display for manual enrollments. Default: Students are added by instructors.', 'cp' ); ?>
								</li>
								<li><span>prerequisite_text</span>
									– <?php _e( 'Text to display when there is a prerequisite. Use %s as placeholder for prerequisite field trip title.  Default: Students need to complete "%s" first.', 'cp' ); ?>
								</li>
								<li><span>passcode_text</span>
									– <?php _e( 'Text to display when a passcode is required. Default: A passcode is required to enroll.', 'cp' ); ?>
								</li>
								<li><span>anyone_text</span>
									– <?php _e( 'Text to display when anyone can enroll. Default: Anyone', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_enrollment_type]<br/>[field_enrollment_type field_id="42"]<br/>[field_enrollment_type
								passcode_text="Whats the magic word?"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Group Size', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_class_size]</span><br/>
							<span class=""><?php _e( 'Shows the field trip field trip group size, limits and remaining seats.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>show_no_limit</span>
									– <?php _e( 'If "yes" it will show the no_limit_text. If "no" then nothing will display for unlimited field trips. Default: no', 'cp' ); ?>
								</li>
								<li><span>show_remaining</span>
									– <?php _e( 'If "yes" show remaining_text. If "no" don’t show remaining places. Default: "Yes"', 'cp' ); ?>
								</li>
								<li><span>no_limit_text</span>
									– <?php _e( 'Text to display for unlimited field trip group sizes. Default: Unlimited', 'cp' ); ?>
								</li>
								<li><span>remaining_text</span>
									– <?php _e( 'Text to display for remaining places. Use %d for the remaining number. Default: (%d places left)', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_class_size]<br/>[field_class_size field_id="42" no_limit_text="The more the
								merrier"]<br/>[field_class_size remaining_text="Only %d places remaining!"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Cost', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_cost]</span><br/>
							<span class=""><?php _e( 'Shows the pricing for the field trip or free for unpaid field trips.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>no_cost_text</span>
									– <?php _e( 'Text to display for unpaid field trips. Default: FREE', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_cost]<br/>[field_cost no_cost_text="Free as in beer."]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Time Estimation', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_time_estimation]</span><br/>
							<span class=""><?php _e( 'Shows the total time estimation based on calculation of stop elements.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>wrapper</span>
									– <?php _e( 'Wrap inside a div tag (yes|no). Default: no', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_time_estimation field_id="42" wrapper="yes"]<br />[field_time_estimation field_id="42"]<br />[field_time_estimation wrapper="yes"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Language', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_language]</span><br/>
							<span class=""><?php _e( 'Displays the language of the field trip (if set).', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code> [field_language]<br/>[field_language label="Delivered in"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip List Image', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_list_image]</span><br/>
							<span class=""><?php _e( 'Displays the field trip list image. (See [field_media])', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>width</span> – <?php _e( 'Width of image. Default: Original width', 'cp' ); ?>
								</li>
								<li><span>height</span>
									– <?php _e( 'Height of image. Default: Original height', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_list_image]<br/>[field_list_image width="100" height="100"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Featured Video', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_featured_video]</span><br/>
							<span class=""><?php _e( 'Embeds a video player with the field trip’s featured video. (See [field_media])', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>width</span>
									– <?php _e( 'Width of video player. Default: Default player width', 'cp' ); ?></li>
								<li><span>height</span>
									– <?php _e( 'Height of video player. Default: Default player height', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_featured_video]<br/>[field_featured_video width="320" height="240"]</code>
						</td>
					</tr>

					<!--					<tr>-->
					<!--						<th scope="row" class="cp-shortcode-title">-->
					<?php //_e( 'Field Thumbnail', 'cp' ); ?><!--</th>-->
					<!--						<td>-->
					<!--							<span class="cp-shortcode-code">[field_thumbnail]</span><br />-->
					<!--							<span class="">-->
					<?php //_e( 'Shows the field trip thumbnail image that is generated from list image. (See [field_media])', 'cp' ); ?><!--</span>-->
					<!---->
					<!--							<p class="cp-shortcode-subheading">-->
					<?php //_e( 'Required Attributes:', 'cp' ); ?><!--</p>-->
					<!---->
					<!--							<ul class="cp-shortcode-options">-->
					<!--								<li><span>field_id</span> – -->
					<?php //_e( 'If outside of the WordPress loop.', 'cp' ); ?><!--</li>-->
					<!--							</ul>-->
					<!---->
					<!--							<p class="cp-shortcode-subheading">-->
					<?php //_e( 'Optional Attributes:', 'cp' ); ?><!--</p>-->
					<!---->
					<!--							<ul class="cp-shortcode-options">-->
					<!--								<li><span>wrapper</span> – -->
					<?php //_e( 'The HTML tag to wrap around the thumbnail. Default: figure', 'cp' ); ?><!--</li>-->
					<!--								<li><span>class</span> – -->
					<?php //_e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?><!--</li>-->
					<!--							</ul>-->
					<!--		-->
					<!--							<p class="cp-shortcode-subheading">-->
					<?php //_e( 'Examples:', 'cp' ); ?><!--</p>-->
					<!--							<code>[field_thumbnail]<br />[field_thumbnail field_id="22" wrapper="div"]</code>-->
					<!--						</td>-->
					<!--					</tr>-->

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Media', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_media]</span><br/>
							<span class=""><?php _e( 'Displays either the list image or the featured video (with the other option as possible fallback).', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>type</span>
									– <?php _e( 'Use "image" to only display list image if available. Use "video" to only show the video if available. Use "thumbnail" to show the field trip thumbnail (shortcut for type="image" and priority="image"). Use "default" to enable priority mode (see priority attribute). Default: FieldPress Settings', 'cp' ); ?>
								</li>
								<li><span>priority</span>
									– <?php _e( 'Use "image" to try to show the list image first. If not available, then try to use the featured video.  Use "video" to try to show the featured video first. If not available, try to use the list image. Default: FieldPress Settings', 'cp' ); ?>
								</li>
								<li><span>list_page</span>
									– <?php _e( 'Use "yes" to use the FieldPress Settings for "Field Trip Listings". Use "no" to use the FieldPress Settings for "Field Trip Details Page". Default: no', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>


							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_media]<br/>[field_media list_page="yes"]<br/>[field_media type="video"]<br/>[field_media
								priority="image"]<br/>[field_media type="thumbnail"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Join Button', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_join_button]</span><br/>
							<span class=""><?php _e( 'Shows the Join/Signup/Enroll button for the field trip. What it displays is dependent on the field trip settings and the user’s status/enrollment.<br />See the attributes for possible button labels.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_full_text</span>
									– <?php _e( 'Text to display if the field trip is full. Default: Field Trip Full', 'cp' ); ?>
								</li>
								<li><span>field_expired_text</span>
									– <?php _e( 'Text to display when the field trip has expired. Default: Not available', 'cp' ); ?>
								</li>
								<li><span>enrollment_finished_text</span>
									– <?php _e( 'Text to display when enrollments are finished (expired). Default: Enrollments Finished', 'cp' ); ?>
								</li>
								<li><span>enrollment_closed_text</span>
									– <?php _e( 'Text to display when enrollments haven’t started yet. Default: Enrollments Closed', 'cp' ); ?>
								</li>
								<li><span>enroll_text</span>
									– <?php _e( 'Text to display when field trip is ready for registration. Default: Register Now', 'cp' ); ?>
								</li>
								<li><span>signup_text</span>
									– <?php _e( 'Text to display when field trip is ready for registration, but the user is not logged in (visitor). Default: Signup!', 'cp' ); ?>
								</li>
								<li><span>details_text</span>
									– <?php _e( 'Text for the button that takes you to the full field trip page. Default: Field Trip Details', 'cp' ); ?>
								</li>
								<li><span>prerequisite_text</span>
									– <?php _e( 'Text to display if the field trip has a prerequisite. Default: Pre-requisite Required', 'cp' ); ?>
								</li>
								<li><span>passcode_text</span>
									– <?php _e( 'Text to display if the field trip requires a password. Default: Passcode Required', 'cp' ); ?>
								</li>
								<li><span>not_started_text</span>
									– <?php _e( 'Text to display when a student is enrolled, but the field trip hasn’t started yet. Default: Not available', 'cp' ); ?>
								</li>
								<li><span>access_text</span>
									– <?php _e( 'Text to display when the user is enrolled and ready to learn. Default: Start Field Trip', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_join_button]<br/>[field_join_button field_id="11" field_expired_text="You
								missed out big time!"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Action Links', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_action_links]</span><br/>
							<span class=""><?php _e( 'Shows  "Field Trip Details" and "Withdraw" links to students.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_action_links]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Calendar', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_calendar]</span><br/>
							<span class=""><?php _e( 'Shows the field trip calendar (bounds are restricted by field trip start and end dates). Will always attempt to show today’s date on a calendar first.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>month</span>
									– <?php _e( 'Month to display as number (e.g. 03 for March). Default: Today’s date', 'cp' ); ?>
								</li>
								<li><span>year</span>
									– <?php _e( 'Year to display as 4-digit number (e.g. 2014). Default: Today’s date', 'cp' ); ?>
								</li>
								<li><span>pre</span>
									– <?php _e( 'Text to display for previous month link. Default: « Previous', 'cp' ); ?>
								</li>
								<li><span>next</span>
									– <?php _e( 'Text to display for next month link. Default: Next »', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_calendar]<br/>[field_calendar pre="< Previous" next="Next >"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip List', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_list]</span><br/>
							<span class=""><?php _e( 'Displays a listing of field trips. Can be for all field trips or restricted by instructors or students (only one or the other, if both specified only students will be used).', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>status</span>
									– <?php _e( 'The status of field trips to show (uses WordPress status). Default: published', 'cp' ); ?>
								</li>
								<li><span>instructor</span>
									– <?php _e( 'The instructor id to list field trips for a specific instructor. Can also specify multiple instructors using commas. (e.g. instructor="1,2,3"). Default: empty', 'cp' ); ?>
								</li>
								<li><span>student</span>
									– <?php _e( 'The student id to list field trips for a specific student. Can also specify multiple students using commas. (e.g. student="1,2,3"). Default: empty', 'cp' ); ?>
									<br/>
									<strong>Note:</strong> If both student and instructor are specified, only the
									student will be used.
								</li>
								<li><span>two_column</span>
									– <?php _e( 'Use "yes" to display primary fields in left column and actions in right column. Use "no" for a single column. Default: yes', 'cp' ); ?>
								</li>
								<li><span>left_class</span>
									– <?php _e( 'Additional CSS classes for styling the left column (if selected). Default: empty', 'cp' ); ?>
								</li>
								<li><span>right_class</span>
									– <?php _e( 'Additional CSS classes for styling the right column (if selected). Default: empty', 'cp' ); ?>
								</li>
								<li><span>title_link</span>
									– <?php _e( 'Use "yes" to turn titles into links to the field trip. Use "no" to display titles without links. Default: yes', 'cp' ); ?>
								</li>
								<li><span>title_tag</span>
									– <?php _e( 'The HTML element (without brackets) to use for field trips titles. Default: h3', 'cp' ); ?>
								</li>
								<li><span>title_class</span>
									– <?php _e( 'Additional CSS classes for styling the field trip titles. Default: empty', 'cp' ); ?>
								</li>
								<li><span>show</span>
									– <?php _e( 'The fields to show for the field trip body. See [field trip] shortcode. Default: dates,enrollment_dates,class_size,cost', 'cp' ); ?>
								</li>
								<li><span>show_button</span>
									– <?php _e( 'Show [field_join_button]. Accepts "yes" and "no". Default: yes', 'cp' ); ?>
								</li>
								<li><span>show_divider</span>
									– <?php _e( 'Add divider between field trips. Accepts "yes" or "no". Default: yes', 'cp' ); ?>
								</li>
								<li><span>show_media</span>
									– <?php _e( 'Show [field_media] if "yes". Default: no', 'cp' ); ?></li>
								<li><span>media_type</span>
									– <?php _e( 'Type to use for media. See [field_media]. Default: FieldTripPress settings for Field Trip Listing Pages.', 'cp' ); ?>
								</li>
								<li><span>media_priority</span>
									– <?php _e( 'Priority to use for media. See [field_media]. Default: FieldPress settings for Field Trip Listing Pages.', 'cp' ); ?>
								</li>
								<li><span>field_class</span>
									– <?php _e( 'Additional CSS classes for styling each field trip. Default: empty', 'cp' ); ?>
								</li>
								<li><span>limit</span>
									– <?php _e( 'Limit the number of field trips. Use -1 to show all. Default: -1', 'cp' ); ?>
								</li>
								<li><span>order</span>
									– <?php _e( 'Order the field trips by title. "ASC" for ascending order. "DESC" for descending order. Empty for WordPress default. Default: "ASC"', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling the whole list. Default: empty', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_list]<br/>[field_list instructor="2"]<br/>[field_list student="3"]<br/>[field_list
								instructor="2,4,5"]<br/>[field_list show="dates,cost" limit="5"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Featured Field Trip', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_featured]</span><br/>
							<span class=""><?php _e( 'Shows a featured field trip.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span>
									– <?php _e( 'If no id is pecified then it will return empty text.', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>featured_title</span>
									– <?php _e( 'The title to display for the featured field trip. Default: Featured Field Trip', 'cp' ); ?>
								</li>
								<li><span>button_title</span>
									– <?php _e( 'Text to display on the call to action button. Default: Find out more.', 'cp' ); ?>
								</li>
								<li><span>media_type</span>
									– <?php _e( 'Media type to use for featured field trip. See [field_media]. Default: default', 'cp' ); ?>
								</li>
								<li><span>media_priority</span>
									– <?php _e( 'Media priority to use for featured field trip. See [field_media]. Default: video', 'cp' ); ?>
								</li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_featured field_id="42"]<br/>[field_featured field_id="11"
								featured_title="The best we got!"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Stop', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_stop]</span><br/>
							<span class=""><?php _e( 'Displays a tree view of the field trip stop.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Required Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>field_id</span> – <?php _e( 'If outside of the WordPress loop.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>free_text</span>
									– <?php _e( 'Text to show for FREE preview items. Default: Free', 'cp' ); ?></li>
								<li><span>show_title</span>
									– <?php _e( 'Show field trip title in stop, "yes" or "no". Default: "no"', 'cp' ); ?>
								</li>
								<li><span>show_label</span>
									– <?php _e( 'Show label text as tree heading, "yes" or "no". Default: no', 'cp' ); ?>
								</li>
								<li><span>show_divider</span>
									– <?php _e( 'Show divider between major items in the tree, "yes" or "no". Default: yes', 'cp' ); ?>
								</li>
								<li><span>label</span>
									– <?php _e( 'Label to display for the output. Set label to "" to hide the label completely.', 'cp' ); ?>
								</li>
								<li><span>label_tag</span>
									– <?php _e( 'HTML tag (without brackets) to use for the individual labels. Default: strong', 'cp' ); ?>
								</li>
								<li><span>label_delimeter</span>
									– <?php _e( 'Symbol to use after the label. Default is colon (:)', 'cp' ); ?></li>
								<li><span>class</span>
									– <?php _e( 'Additional CSS classes for styling. Default: empty', 'cp' ); ?></li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_stop]<br/>[field_stop field_id="42" free_text="Gratis!"
								show_title="no"]<br/>[field_stop show_title="no" label="Curriculum"]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Field Trip Signup/Login Page', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[field_signup]</span><br/>
							<span class=""><?php _e( 'Shows a custom login or signup page for front-end user registration and login. <strong>Note:</strong> This is already part of FieldPress and can be set in FieldPress Settings. Links to default pages can be found in Appearance > Menus > FieldPress.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Optional Attributes:', 'cp' ); ?></p>

							<ul class="cp-shortcode-options">
								<li><span>failed_login_text</span>
									– <?php _e( 'Text to display when user doesn’t authenticate. Default: Invalid login.', 'cp' ); ?>
								</li>
								<li><span>failed_login_class</span>
									– <?php _e( 'CSS class to use for invalid login. Default: red', 'cp' ); ?></li>
								<li><span>logout_url</span>
									– <?php _e( 'URL to redirect to when user logs out. Default: Plugin defaults.', 'cp' ); ?>
								</li>
								<li><span>signup_title</span>
									– <?php _e( 'Title to use for Signup section. Default: &lt;h3>Signup&lt;/h3>', 'cp' ); ?>
								</li>
								<li><span>login_title</span>
									– <?php _e( 'Title to use for Login section. Default: &lt;h3>Login&lt;/h3>', 'cp' ); ?>
								</li>
								<li><span>signup_url</span>
									– <?php _e( 'URL to redirect to when clicking on "Don\'t have an account? Go to Signup!"  Default: Plugin defaults.', 'cp' ); ?>
								</li>
								<li><span>login_url</span>
									– <?php _e( 'URL to redirect to when clicking on "Already have an Account?". Default: Plugin defaults.', 'cp' ); ?>
								</li>
							</ul>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[field_signup]<br/>[field_signup signup_title="&lt;h1>Signup Now&lt;/h1>"]</code>
						</td>
					</tr>

					<tr class="cp-shortcode-alt">
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Student Dashboard Template', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[fields_student_dashboard]</span><br/>
							<span class=""><?php _e( 'Loads the student dashboard template.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[fields_student_dashboard]</code>
						</td>
					</tr>

					<tr>
						<th scope="row" class="cp-shortcode-title"><?php _e( 'Student Settings Template', 'cp' ); ?></th>
						<td>
							<span class="cp-shortcode-code">[fields_student_settings]</span><br/>
							<span class=""><?php _e( 'Loads the student settings template.', 'cp' ); ?></span>

							<p class="cp-shortcode-subheading"><?php _e( 'Examples:', 'cp' ); ?></p>
							<code>[fields_student_settings]</code>
						</td>
					</tr>

				</table>
			</div>
		</div>

	</form>
</div>