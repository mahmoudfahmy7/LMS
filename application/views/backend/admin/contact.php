<div class="bs-example">
    <a href="<?php echo site_url('admin/unread_contact'); ?>" style="padding: 20px; font-size: 20px;" >
        <i class="fas fa-envelope"></i></a>
    <a href="<?php echo site_url('admin/contact_us'); ?>" style="padding: 20px; font-size: 20px; ">
        <i class="fas fa-envelope-open"></i></a>
</div>

<div class="bs-example">
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Message</th>
            <th>Date</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($contact_us as $i=>$contact)
        {
            echo '  <tr style="font-size: 20px; color: black "  >  
            <td> <a href="'.site_url("admin/showcontact/".$contact->id).'" style="font-size: 16px; color: black " > '.$contact->name.'</a> </td>
            <td> <a href="'.site_url("admin/showcontact/".$contact->id).'" style="font-size: 16px; color: black "> '.$contact->msg.'</a> </td>
            <td> <a href="'.site_url("admin/showcontact/".$contact->id).'" style="font-size: 16px; color: black "> '.date('d-M',$contact->updated_at).'</a> </td>
            <td> <a href="'.site_url("admin/deletecontact/".$contact->id).'" style="font-size: 16px; color: black "><i class="far fa-trash-alt"></i></a> </td>
        
        </tr>';
        }
        ?>
        </tbody>
    </table>
</div>
