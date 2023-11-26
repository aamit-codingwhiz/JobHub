<?php
// Function to get job listings
function getJobListings($db) {
    $sql = "SELECT Jobs.*, CompanyProfiles.*\n"
    . "FROM Jobs\n"
    . "INNER JOIN CompanyProfiles ON Jobs.company_id = CompanyProfiles.company_id;";
    $result = $db->query($sql);
    return ($result->num_rows > 0) ? $result : false;
}
?>
