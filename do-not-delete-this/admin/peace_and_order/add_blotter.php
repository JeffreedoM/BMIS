<?php

//Getting residents from the database
$stmt = $pdo->prepare("SELECT * FROM resident WHERE barangay_id = :barangay_id");
$stmt->bindParam(':barangay_id', $barangayId, PDO::PARAM_INT);
$stmt->execute();
$resident = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    //
    $offender_type = $_POST['o_res'];
    $complainant_type = $_POST['c_res'];
    $blotter_type = $_POST['blotter_type'];


    //insert to incident_reporting_person db
    $complainant_fname = $_POST['c_fname'];
    $complainant_lname = $_POST['c_lname'];
    $complainant_gender = $_POST['c_gender'];
    $complainant_bdate = $_POST['c_bdate'];
    $complainant_number = $_POST['c_number'];
    $complainant_address = $_POST['c_address'];

    //insert to incident_offender db
    $offender_fname = $_POST['o_fname'];
    $offender_lname = $_POST['o_lname'];
    $offender_gender = $_POST['o_gender'];
    $offender_bdate = $_POST['o_bdate'];
    $offender_number = $_POST['o_number'];
    $offender_address = $_POST['o_address'];
    $description = $_POST['desc'];

    //insert to incident_table db
    if ($_POST['i_case'] == 'more') {
        $case_incident = $_POST['case_more'];
    } else {
        $case_incident = $_POST['i_case'];
    }

    $i_title = $_POST['i_title'];
    $i_date = $_POST['i_date'];
    $i_time = $_POST['i_time'];
    $location = $_POST['i_location'];
    $status = 1;
    $narrative = $_POST['narrative'];

    $stmt3 = $pdo->prepare("INSERT INTO incident_table(case_incident, incident_title, date_incident, time_incident, location,status, narrative, blotterType_id) VALUES(:case_incident,:i_title,:i_date,:i_time,:location,:status,:narrative,:blotterType_id)");
    $stmt3->bindParam(':case_incident', $case_incident);
    $stmt3->bindParam(':i_title', $i_title);
    $stmt3->bindParam(':i_date', $i_date);
    $stmt3->bindParam(':i_time', $i_time);
    $stmt3->bindParam(':location', $location);
    $stmt3->bindParam(':status', $status);
    $stmt3->bindParam(':narrative', $narrative);
    $stmt3->bindParam(':blotterType_id', $blotter_type);

    // Execute the third query and get the inserted ID
    $pdo->beginTransaction();
    $stmt3->execute();
    $incident_id = $pdo->lastInsertId();
    $pdo->commit();


    //complainant insert data
    if ($complainant_type === 'resident') {
        // Prepare the first query


        // Prepare the complainant query
        $stmt = $pdo->prepare("INSERT INTO incident_complainant(complainant_type, resident_id, incident_id) VALUES(:complainant_type,:resident_id,:incident_id)");
        $stmt->bindParam(':complainant_type', $complainant_type);
        $stmt->bindParam(':resident_id', $resident_type);
        $stmt->bindParam(':incident_id', $incident_id);

        // Execute the second query and get the inserted ID
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
    } else {
        //Prepare non-resident query
        $stmt = $pdo->prepare("INSERT INTO non_resident(non_res_firstname, non_res_lastname, non_res_contact, non_res_gender, non_res_birthdate, non_res_address) VALUES(:non_res_firstname, :non_res_lastname, :non_res_contact, :non_res_gender, :non_res_birthdate, :non_res_address)");
        $stmt->bindParam(':non_res_firstname', $complainant_fname);
        $stmt->bindParam(':non_res_lastname', $complainant_lname);
        $stmt->bindParam(':non_res_gender', $complainant_gender);
        $stmt->bindParam(':non_res_birthdate', $complainant_bdate);
        $stmt->bindParam(':non_res_contact', $complainant_number);
        $stmt->bindParam(':non_res_address', $complainant_address);
        // Execute the second query and get the inserted ID
        $pdo->beginTransaction();
        $stmt->execute();
        $non_resident_id = $pdo->lastInsertId();
        $pdo->commit();

        // Prepare the complainant query
        $stmt = $pdo->prepare("INSERT INTO incident_complainant(complainant_type, non_resident_id, incident_id) VALUES(:complainant_type,:non_resident_id,:incident_id)");
        $stmt->bindParam(':complainant_type', $complainant_type);
        $stmt->bindParam(':non_resident_id', $non_resident_id);
        $stmt->bindParam(':incident_id', $incident_id);

        // Execute the second query and get the inserted ID
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
    }


    //offender insert data
    if ($offender_type === 'resident') {
        // Prepare the first query
        $stmt = $pdo->prepare("INSERT INTO incident_offender(offender_type, resident_id, incident_id, desc) VALUES(:offender_type,:resident_id,:incident_id, :desc)");
        $stmt->bindParam(':offender_type', $offender_type);
        $stmt->bindParam(':resident_id', $resident_type);
        $stmt->bindParam(':incident_id', $incident_id);
        $stmt->bindParam(':desc', $description);

        // Execute the first query and get the inserted ID
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
    } else {
        //Prepare non-resident query
        $stmt = $pdo->prepare("INSERT INTO non_resident(non_res_firstname, non_res_lastname, non_res_gender, non_res_contact, non_res_birthdate, non_res_address) VALUES(:non_res_firstname, :non_res_lastname, :non_res_gender, :non_res_contact, :non_res_birthdate, :non_res_address)");
        $stmt->bindParam(':non_res_firstname', $offender_fname);
        $stmt->bindParam(':non_res_lastname', $offender_lname);
        $stmt->bindParam(':non_res_gender', $offender_gender);
        $stmt->bindParam(':non_res_contact', $offender_number);
        $stmt->bindParam(':non_res_birthdate', $offender_bdate);
        $stmt->bindParam(':non_res_address', $offender_address);

        $pdo->beginTransaction();
        $stmt->execute();
        $non_resident_id = $pdo->lastInsertId();
        $pdo->commit();

        // Prepare the complainant query
        $stmt = $pdo->prepare("INSERT INTO incident_offender(offender_type, non_resident_id, incident_id, `desc`) VALUES(:offender_type,:non_resident_id,:incident_id, :desc)");
        $stmt->bindParam(':offender_type', $offender_type);
        $stmt->bindParam(':non_resident_id', $non_resident_id);
        $stmt->bindParam(':incident_id', $incident_id);
        $stmt->bindParam(':desc', $description);

        // Execute the first query and get the inserted ID
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();
    }


    header('location: list_incident.php');
}

?>
<!-- INPUT FORMS IN ADD INCIDENT -->
<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="myForm" action="" method="POST">

                    <!-- Complainant -->
                    <h3>Reporting Person/Complainant</h3>
                    <div>

                        <div class="mb-1">
                            <select name="blotter_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected disabled>Select Blotter Type</option>
                                <option value="1">Complaint</option>
                                <option value="2">Incident</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <select onchange="showInput1()" id="res_type" name="c_res" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected disabled>Select Resident Type</option>
                                <option value="resident">Resident</option>
                                <option value="not resident">Non-Resident</option>
                            </select>
                        </div>
                        <div id="c_input">
                            <div id="c_resident" style="display:none;">

                                <!--Modal for selectiung complainant  -->
                                <button data-modal-target="complainantModal" data-modal-toggle="complainantModal" class="block mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                    Select Resident
                                </button>

                                <!-- Modal for List of Residents -->
                                <div id="complainantModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    List of Residents
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="complainantModal">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-6">

                                                <table id="residents-table" class="row-border hover" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>FirstName</th>
                                                            <th>LastName</th>
                                                            <th class="hidden-cell">Phone Number</th>
                                                            <th class="hidden-cell">Gender</th>
                                                            <th class="hidden-cell">Birthdate</th>
                                                            <th class="hidden-cell">Address</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($resident as $resident) { ?>
                                                            <tr id="<?php echo $resident['resident_id'] ?>" style="cursor:pointer" data-modal-hide="complainantModal">
                                                                <td><?php echo $resident['resident_id'] ?></td>
                                                                <td><?php echo $resident['firstname'] ?></td>
                                                                <td><?php echo $resident['lastname'] ?></td>
                                                                <td class="hidden-cell"><?php echo $resident['contact'] ?></td>
                                                                <td class="hidden-cell"><?php echo $resident['sex'] ?></td>
                                                                <td class="hidden-cell"><?php echo $resident['birthdate'] ?></td>
                                                                <td class="hidden-cell"><?php echo $resident['address'] ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Name -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="hidden" name="complainant_id" class="complainant_id">
                                    <input type="text" name="c_fname" id="complainant_fname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First name</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="c_lname" id="complainant_lname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last name</label>
                                </div>
                            </div>

                            <!-- Number -->
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="tel" pattern="[0-9]{4}[0-9]{3}[0-9]{4}" name="c_number" id="contact" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number</label>
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                                <select id="gender" name="c_gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <!--Birthdate -->
                            <div class="mb-5">
                                <label for="">Birthdate <span class="required-input">*</span></label>
                                <div class="relative w-1/2 sm:w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input datepicker type="text" name="c_bdate" id="bdate" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                                </div>
                            </div>

                            <!--Address -->
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="text" name="c_address" id="address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                <label for="floating_address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                    Address</label>
                            </div>
                        </div>
                        <br><br>

                        <!-- OFFENDER INPUTS-->
                        <h3>Offender</h3>
                        <!--horizontal line -->
                        <hr>
                        <div id="o_input" style="margin-top: 10px;">
                            <div class="mb-3">
                                <select onchange="showInput2()" id="res_type2" name="o_res" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected disabled>Select Resident Type</option>
                                    <option value="resident">Resident</option>
                                    <option value="not resident">Non-Resident</option>
                                </select>
                            </div>
                            <div id="o_resident" style="display:none;">
                                <!--Modal resident button -->
                                <!-- <button class="add-resident__button" onclick="openPopup()">
                                    <label for="position" class="block font-medium text-red-500 dark:text-white">Select resident <i class="fa-solid fa-caret-down ml-1"></i></label>
                                </button> -->
                                <!--Modal for selectiung complainant  -->
                                <button data-modal-target="complainantModal" data-modal-toggle="complainantModal" class="block mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                    Select Resident
                                </button>
                            </div>

                            <!-- Name -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="o_fname" id="" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First name</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="o_lname" id="" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                    <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last name</label>
                                </div>
                            </div>

                            <!-- Number -->
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="tel" pattern="[0-9]{4}[0-9]{3}[0-9]{4}" name="o_number" id="" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                <label for="" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number</label>
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                                <select name="o_gender" id="o_gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <!--Birthdate -->
                            <div class="mb-5">
                                <label for="">Birthdate <span class="required-input">*</span></label>
                                <div class="relative w-1/2 sm:w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input datepicker type="text" name="o_bdate" id="" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                                </div>
                            </div>

                            <!--Address -->
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="text" name="o_address" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                <label for="floating_address" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                    Address</label>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea name="desc" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter narrative..."></textarea>
                            </div>
                        </div>


                        <!-- INCIDENT DETAILS -->
                        <!-- criminal case -->
                        <br>
                        <br>
                        <br>
                        <h3>Incident Details</h3>
                        <hr>
                        <div class="flex" style="margin-top: 10px;">
                            <div class="flex items-center mr-4">
                                <input onclick="showInput()" id="" type="radio" value="criminal" name="i_case" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Criminal</label>
                            </div>
                            <div class="flex items-center mr-4">
                                <input onclick="showInput()" id="" type="radio" value="civil" name="i_case" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Civil</label>
                            </div>
                            <div class="flex items-center mr-4">
                                <input onclick="showInput()" type="radio" id="i_others" name="i_case" value="more" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="inline-checked-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Others</label>
                            </div>
                            <div id="otherInput" style="display:none;">
                                <input type="text" name="case_more" class="block w-1/2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="other case...">
                            </div>
                        </div>

                        <!-- incident title -->
                        <div class="my-3">
                            <label>Incident Title</label>
                            <input type="text" name="i_title" required class="block w-1/2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>

                        <!-- date -->
                        <div class="mb-3">
                            <label>Date of Incident</label>
                            <input type="date" name="i_date" required class="block w-1/2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>

                        <!-- time -->
                        <div class="mb-3">
                            <label>Time of Incident</label>
                            <input type="time" name="i_time" required class="block w-1/2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>

                        <!-- location -->
                        <div class="mb-3">
                            <label>Location of incident</label>
                            <input type="text" name="i_location" required class="block w-1/2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>
                        <!-- Narrative -->
                        <div>
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Narrative</label>
                            <textarea name="narrative" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer" class="mt-2">
                        <button name="submit" type="submit" class="w-full mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save Changes</button>
                    </div>
                </form>
            </div>


        </div>

    </div>
</div>