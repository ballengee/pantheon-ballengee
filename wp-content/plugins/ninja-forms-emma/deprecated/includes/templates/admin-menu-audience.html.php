<form action="" method="POST">

    <input type="hidden" name="action" value="save" />

    <table class="form-table">
        <tbody>

            <?php if( $this->emma ): ?>

            <tr id="row_groups">
                <th scope="row">
                    <label for="version"><?php _e( 'Group List', 'ninja-forms-emma' ); ?></label>
                </th>
                <td>
                    <ul>
                        <?php foreach( $this->emma->list_groups( 'all' ) as $group ): ?>
                        <li>
                            <strong><?php echo $group->group_name; ?></strong> - <em><?php echo $this->group_types[ $group->group_type ]; ?></em>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>

            <tr id="row_add_group_name">
                <th scope="row">
                    <label for="emma_add_group_name"><?php _e( 'New Group Name', 'ninja-forms-emma' ); ?></label>
                </th>
                <td>
                    <input type="text" class="widefat" name="emma_add_group[name]" />
                </td>
            </tr>

            <tr id="row_add_group_type">
                <th scope="row">
                    <label for="emma_add_group_type"><?php _e( 'New Group Type', 'ninja-forms-emma' ); ?></label>
                </th>
                <td>
                    <select name="emma_add_group[type]">
                        <?php foreach( $this->group_types as $index => $value): ?>
                            <option value="<?php echo $index; ?>">
                                <?php echo $value; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th colspan="2">
                    <input type="submit" class="button button-primary" value="<?php _e( 'Add New Group', 'ninja-forms-emma' ); ?>"/>
                </th>
            </tr>

            <?php else: ?>
                <tr>
                    <td>
                        <span class=""><?php _e( 'No groups were found. Please confirm your', 'ninja-forms-emma' ); ?> <a href="<?php echo admin_url( 'admin.php?page=ninja-forms-emma' ); ?>"><?php _e( 'Emma', 'ninja-forms-emma' ); ?> <?php _e( 'Settings', 'ninja-forms-emma' ); ?></a>.</span>
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>

    </table>
</form>