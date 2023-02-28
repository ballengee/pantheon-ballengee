<!-- SETTING: GROUPS -->
<tr>
    <th scope="row">
        <label for="settings[groups']"><?php _e( 'Groups', 'ninja-forms-emma' ); ?></label>
    </th>
    <td>
        <?php if( is_array( $this->groups ) AND ! empty( $this->groups ) ): ?>
            <select name="settings[groups]" id="settings-groups">

                <?php foreach( $this->groups as $group ): ?>
                    <option value="<?php echo $group->member_group_id; ?>"<?php if( in_array( $group->member_group_id, $settings['groups'] ) ) echo " selected"; ?>>
                        <?php echo $group->group_name; ?>
                    </option>
                <?php endforeach; ?>

            </select>
        <?php else: ?>
            <span class=""><?php _e( 'No groups were found. Please confirm your', 'ninja-forms-emma' ) ;?> <a href="<?php echo admin_url( 'admin.php?page=ninja-forms-emma' ); ?>"><?php _e( 'Emma', 'ninja-forms-emma' ); ?> <?php _e( 'Settings', 'ninja-forms-emma' ); ?></a>.</span>
        <?php endif; ?>
        <span class="howto"><?php _e( 'The Group(s) to which the new member should be added.', 'ninja-forms-emma' ) ;?></span>
        <?php if( isset( $_GET['debug'] ) ) { echo "<pre>"; var_dump($settings['groups']); echo "</pre>"; } ?>
    </td>
</tr>





<!-- SETTING: FIRST NAME FIELD -->
<tr>
    <th scope="row">
        <label for="settings[first-name-field]"><?php _e( 'First Name Field', 'ninja-forms-emma' ); ?></label>
    </th>
    <td>
        <select name="settings[first-name-field]" id="settings-first-name-field">
            <option></option>
            <?php foreach( $form->fields as $field ): ?>
                <?php if( '_text' == $field['type'] ): ?>
                <option value="<?php echo $field['id']; ?>"<?php if( $field['id'] == $settings['first-name-field'] ) echo " selected"; ?>>
                    <?php echo $field['data']['label']; ?> (ID: <?php echo $field['id']; ?>)
                </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <span class="howto"><?php _e( "The Field containing the new member's First Name.", 'ninja-forms-emma' ) ;?></span>
        <?php if( isset( $_GET['debug'] ) ) { echo "<pre>"; var_dump($settings['first-name-field']); echo "</pre>"; } ?>
    </td>
</tr>





<!-- SETTING: LAST NAME FIELD -->
<tr>
    <th scope="row">
        <label for="settings[last-name-field]"><?php _e( 'Last Name Field', 'ninja-forms-emma' ); ?></label>
    </th>
    <td>
        <select name="settings[last-name-field]" id="settings-last-name-field">
            <option></option>
            <?php foreach( $form->fields as $field ): ?>
                <?php if( '_text' == $field['type'] ): ?>
                    <option value="<?php echo $field['id']; ?>"<?php if( $field['id'] == $settings['last-name-field'] ) echo " selected"; ?>>
                        <?php echo $field['data']['label']; ?> (ID: <?php echo $field['id']; ?>)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <span class="howto"><?php _e( "The Field containing the new member's Last Name.", 'ninja-forms-emma' ) ;?></span>
        <?php if( isset( $_GET['debug'] ) ) { echo "<pre>"; var_dump($settings['last-name-field']); echo "</pre>"; } ?>
    </td>
</tr>





<!-- SETTING: EMAIL FIELD -->
<tr>
    <th scope="row">
        <label for="settings[email-field]"><?php _e( 'Email Field', 'ninja-forms-emma' ); ?></label>
    </th>
    <td>
        <select name="settings[email-field]" id="settings-email-field">
            <option></option>
            <?php foreach( $form->fields as $field ): ?>
                <?php if( '_text' == $field['type'] ): ?>
                    <option value="<?php echo $field['id']; ?>"<?php if( $field['id'] == $settings['email-field'] ) echo " selected"; ?>>
                        <?php echo $field['data']['label']; ?> (ID: <?php echo $field['id']; ?>)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <span class="howto"><?php _e( "The Field containing the new member's Email Address.", 'ninja-forms-emma' ) ;?></span>
        <?php if( isset( $_GET['debug'] ) ) { echo "<pre>"; var_dump($settings['email-field']); echo "</pre>"; } ?>
    </td>
</tr>





<!-- SETTING: OPT-IN FIELD -->
<tr>
    <th scope="row">
        <label for="settings[optin-field]"><?php _e( 'Opt-In Field', 'ninja-forms-emma' ); ?></label>
    </th>
    <td>
        <select name="settings[optin-field]" id="settings-optin-field">
            <option value="0"><?php _e( 'None', 'ninja-forms-emma' ); ?></option>
            <?php foreach( $form->fields as $field ): ?>
                <?php if( '_checkbox' == $field['type'] ): ?>
                    <option value="<?php echo $field['id']; ?>"<?php if( $field['id'] == $settings['optin-field'] ) echo " selected"; ?>>
                        <?php echo $field['data']['label']; ?> (ID: <?php echo $field['id']; ?>)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <span class="howto"><?php _e( 'Optional. The Field used to opt-in to signup.', 'ninja-forms-emma' ) ;?></span>
        <?php if( isset( $_GET['debug'] ) ) { echo "<pre>"; var_dump($settings['optin-field']); echo "</pre>"; } ?>
    </td>
</tr>