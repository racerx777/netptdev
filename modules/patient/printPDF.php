<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dompdf/autoload.inc.php');
require_once('patientPrintFormsFunctions.php');

use Dompdf\Dompdf;



if (isset($_POST['pdf_id'])) {
    $id = $_POST['pdf_id'];
    $lang = $_POST['lng'];
    if (is_numeric($_POST['pdf_id'])) {
        $data = patientPrintIntakeForms($id, $casetype = 'WC', $lang);
        if (!empty($data["patient"]["ssn"])) {
            $dataSnn = substr($data["patient"]["ssn"], 7);
            $dataSnn = 'XXX-XX' . $dataSnn;
        }
        if (!empty($data["employer"]["city"] && $data["employer"]["state"] && $data["employer"]["zip"])) {
            $empAddress = $data["employer"]["city"] . ',' . $data["employer"]["state"] . ',' . $data["employer"]["zip"];
        } else {
            $empAddress = "";
        }

        if (!empty($data["attorney"]["city"] && $data["attorney"]["state"] && $data["attorney"]["zip"])) {
            $attorneyAddress = $data["attorney"]["city"] . ',' . $data["attorney"]["state"] . ',' . $data["attorney"]["zip"];
        } else {
            $attorneyAddress = "";
        }
        $currentDate = date("m/d/y");
    }




    if (isset($id) && isset($lang)) {

        $today = date("m/d/Y", time());

        if ($lang == 'en') {


            $title = "Patient Information and Treatment Authorization";
            $doc_date = "Document Date :";
            $pat_info = "PATIENT INFORMATION #";
            $name = "Name";
            $address = "Address";
            $sex = "Sex";
            $citysZ = "City, Zip:";
            $dob = "DOB";
            $date = "Date";
            $phone = 'Phone';
            $homeph = "Home Ph";
            $age = "Age";
            $workph = "Work Ph";
            $cellph = "Cell Ph";
            $inj_title = "INJURY INFORMATION";
            $inj_info = "INJURY INFORMATION";
            $post_sx = "Post Sx";
            $type = "Type";
            $sx_date = "Sx Date";
            $body_pts = "Body Pts";
            $refer_title = 'REFERRING DOCTOR INFORMATION';
            $att_title = "ATTORNEY INFORMATION";
            $emp_title = "EMPLOYMENT INFORMATION";
            $prm_insu = "PRIMARY INSURANCE INFORMATION";
            $sec_insu = "SECONDARY INSURANCE INFORMATION";
            $ins_name = "Ins Name";
            $rel_title = "RELEASE OF INFORMATION and ASSIGNMENT OF BENEFITS";
            $auth_title = "I hereby authorize WestStar Physical Therapy to release information requested by my insurance carrier concerning this illness upon request. I hereby authorize direct payment of my insurance benefits to WestStar Physical Therapy for services rendered.";
            $date_signed = "Date Signed";
            $patient = "Patient";
            $pain_info = "PAIN INFORMATION";

            $job = "JOB INFORMATION #";
            $job_title = "Job Title:";

            $job_desc = "Job Description:";
            $add_job_details = "ADDITIONAL JOB DETAILS";
            $hours = "Hours";
            //value for 3 pdf page
            $during = "During a typical 8-hour day, How many hours do you...?";





            $sit = "Sit :";
            $stand = "Stand :";
            $walk = "Walk :";
            $drive = "Drive :";
            $at_work = "At work, on average, how many hours do you work per...";
            $day_shift = "Day/Shift :";
            $week = "Week :";
            $at_wokr_spend = "At work, on average, how much time do you spend...?";
            $squatting = "Squatting : ";
            $stooping = "Stooping/bending :";
            $kneeling = "Kneeling : ";
            $reaching_up = "Reaching Up :";
            $reaching_out = "Reaching Out :";
            $twisting = "Twisting :";
            $crawling = "Crawling :";
            $stair_clm = "Stair Climbing :";
            $ladder_clm = "Ladder Climbing :";
            $using_com = "Using a Computer :";
            $using_tel = "Using the Telephone :";
            $pushing = "Pushing :";
            $pulling = "Pulling :";
            $lifting_overhead = "Lifting Overhead :";


            $constantly = "Constantly";
            $Often = "Often";
            $Sometimes = "Sometimes";
            $Never = "Never";



            $at_work_lift = "At work, my job requires that I lift .. ";
            $lbs10 = "10 lbs or less :";
            $lbs11 = "11 lbs to 25 lbs :";
            $lbs26 = "26 lbs to 50 lbs :";
            $lbs51 = "51 lbs to 75 lbs :";
            $lbs76 = "76 lbs to 100 Ibs :";
            $lbs100 = "over 100 Ibs :";
            $at_work_inc = "At work, my job includes... ";
            $Repetitive1 = "Repetitive Hand Movement :";
            $Repetitiv2 = "Repetitive Foot Movement :";
            $power_grp = "Power Gripping :";
            $precision = "Precision Handling :";
            $balancing = "Balancing :";
            $use_computer_mouse = "Use of computer mouse/touch pad :";
            $timed_work = "Timed work for efficiency :";
            $simultaneous = "Simultaneous computer & telephone :";


            $emergency_room = "Did you go to the Emergency Room at a Hospital?";
            $brief_injury = "Briefly describe your injury";
            $emeregency_medical_fac = "If not an Emergency Room, Ad you go to some other type of medical facility?";
            $xray = "Were x-rays taken?";
            $auto_accident = "If an auto accident, was the vehicle drivable after the accident?";
            $previous_injury = "Do you have any previous injury to the sense area?";
            $being_treted = "Are you still being treated for this injury?";
            $treted = "If you are still being treated for this injury, by whom?";
            $ssn = "SSN";
            //value for 4 pdf page 

            $draw_location = "Draw the location of your pain on the body outlines using the following markers.";
            $aches = "Aches";
            $burning = "Burning";
            $nurnbness = "Nurnbness";
            $pinsNeedles = "Pins & Needles";
            $stabbing = "Stabbing";
            $other = "Other";
            $waver_info = "WAIVER INFORMATION";
            $waver_info_details = "I, AM OF LEGAL AGE AND HEREBY CERTIFY THAT I WENT TO WEST STAR PHYSICAL THERAPY
            OF MY OWN DISCRETION AND DECISION TO RECEIVE PHYSICAL THERAPY TREATMENTS. I
            UNDERSTAND THAT I MAY OR MAY NOT HAVE A DOCTORS REFERRAL AND THAT GETTING
            PHYSICAL THERAPY IS MY TREATMENT OF CHOICE. I ALSO UNDERSTAND THAT I WILL BE
            EVALUATED BY A LICENSED AND CERTIFIED PHYSICAL THEREAPIST AND THAT THE
            THERAPISTS EVALUATION AND RECOMMENDATION WILL BE EXPLAINED TO ME BEFORE
            TREATMENT. I UNDERSTAND THAT THE PHYSICAL THERAPIST WILL COMMUNICATE WITH MY
            MEDICAL DOCTOR TO GET AUTHORIZATION FOR MY PHYSICAL THERAPY TREATMENTS. I ALSO
            UNDERSTAND THAT I CANNOT RECEIVE PHYSICAL THERAPY TREATMENTS FROM WEST STAR
            PHYSICAL THERAPY WITHOUT SIGNED AUTHORIZATION FROM MY MEDICAL DOCTOR.
            FURTHERMORE, I UNDERSTAND THAT PHYSICAL THERAPY, WHILE DESIGNED TO, IS NOT
            GUARANTEED TO IMPROVE MY CURRENT CONDITION.";


            $if_min = "IF MINOR:";
            $name_of_parent = "NAME OF PARENT OF GUARDIAN:";
            $relationship = "RELATIONSHIP:";
            $patient_sign = "PATIENT SIGNATURE:";

            $witnessed = "WITNESSED BY:";
            $name_of_staff = "NAME OF STAFF MEMBER:";
            $sign_date = "SIGNATURE:";
            $waiver_title = "Waiver";

            // value for 6 pdf 
            $notice_head = "Notice of Privacy Practices";
            $privacy_info = "PRIVACY INFORMATION";
            $page = "Page (1 of 3)";
            $notice_descibe = "THIS NOTICE DESCRIBES HOW MEDICAL INFORMATION ABOUT YOU MAY BE USED AND
            DISCLOSED AND HOW YOU CAN GET ACCESS TO THIS INFORMATION. PLEASE REVIEW IT
            CAREFULLY";
            $uses_dis = "Uses and Disclosures";

            $treatment_health = "Treatment: Your health information may be used by staff members or disclosed to other health care professionals
            for the purpose of evaluating your health, diagnosing medical conditions and providing treatment. For Example,
            results of laboratory tests and procedures will be available in your medical record to all health professionals who
            may provide treatment or who may be consulted by staff members.";

            $payment_health = "Payment: Your health information may be used to seek payment from your health plan, from other sources of
            coverage such as an automobile insurer, or from credit card companies that you may use to pay for services. For
            example, your health plan may request and receive information on dates of service, services provided and the
            medical condition being treated.";

            $health_care_operation = "Health Care Operations: Your health information may be used as necessary to support the day-to-day activities of
            and management of West Star Physical Therapy. For Example, information on the services you received may be
            used to support budgeting and financial reporting and activities to evaluate and promote quality.";

            $law_enforcement = "Law Enforcement: your health information may be disclosed to law enforcement agencies to support government
            audits and inspections, to facilitate law enforcement investigations and to comply with government mandated
            reporting.";

            $public_health = "Public Health Reporting: Your health care information may be disclosed to public health agencies as required by
            law. For example, we are required to report certain communicable diseases to the states public health department.";

            $other_uses = "Other Uses and Disclosures That Require Your Authorization: Disclosure of your health information or its use for
            any purpose other than those listed above requires your specific, written authorization. However, your decision to
            revoke authorization will not affect of undo any use or disclosure of information that occurred before you notified
            us of your decision to revoke your authorization.";




            $appointment_reminder = "Appointment Reminders: Your health information will be used by our staff to send you appointment reminders.";
            $page2 = "Page (2 of 3)";
            $info_about_tratment = "Information About Treatments: Your health information may be used to send you information that you may find
interesting on the treatment and management of your medical condition. From our database, we may also send you
information describing only West Star related information that may be of interest to you**";


            $dont_use_info = "Please do not use my health information for the above-mentioned services.";


            $individual_right = "Individual Rights: You have certain rights under the federal privacy standards. These include:";


            $individual_right1 = "The right to request restrictions on the use and disclosure of your protected health care information;";

            $individual_right2 = "The right to receive confidential communications concerning your medical condition and treatment;";


            $individual_right3 = "The right to inspect and copy your protected health information;";



            $individual_right4 = "The right to amend or submit corrections to your protected health care information;";



            $individual_right5 = "The right to receive an accounting of how and to whom your protected health information has been disclosed;";


            $individual_right6 = "The right to receive a printed copy of this notice";



            $west_star = "West Star Physical Therapy Duties: We are required by law to maintain the privacy of your protected health
information and to provide you with this notice of privacy practices.";


            $abide = "We are also required to abide by the privacy policies and practices that are outlined in this notice.";

            $right_to_reserve = "Right to Revise Privacy Practices: As permitted by law, we reserve the right to amend to modify our privacy
policies and practices. These changes in our policies and practices may be required by changes in federal and state
laws and regulations. Upon request, we will provide you with the most recently revised notice on any office visits.
The revise policies and practices will be applied to all protected health information we maintain.";


            $request_to_inspect = "Requests to Inspect Protected Health Information: You may generally inspect or copy the protected health
information we maintain. As permitted by Federal Regulations we require that requests to inspect or copy
protected health information be submitted in writing. You may obtain a form to request access to your records by
contacting our corporate office. Your request will be reviewed and will generally be approved unless there are
legal or medical reasons to deny the request";


            $complaints = "Complaints: If you would like to submit a comment or complaint about our privacy practices, you can do so by
sending a letter outlining your concerns to:";


            $adress1 = "West Star Physical Therapy";
            $address2 = "5400 Orange Street";

            $address2 = "Suite #215";
            $address2 = "Cypress, CA 90630";


            $belive_your_priv = "If you believe that your privacy rights have been violated, you should call the matter to our attention by sending a
letter describing the cause of concern to the same address. You will not be penalized or otherwise retaliated against
for filing a complaint.";

            $contact_person = "Contact Person: The address of the person you may contact for further information consenting privacy practices is:";

            $west = "West Star Physical Therapy";


            $address5 = "PO Box 6209";
            $address6 = "Garden Grove, CA N846";


            $effective_date = "Effective Date: This notice is effective as of May 18, 2012";


            $ack = "Acknowledgement of Receipt of Notice of Privacy Practices";

            $ack_recived = "I, have received, read and fully understand the Notice of Privacy Practices for West Star Physical therapy and
acknowledge and understand that West Stat Physical therapy reserves the right to modify or amend the privacy
practices outlined in the notice.";


            $patient_representative = "Patient Representative is required if the patient is a minor or patient is an adult who is unable to sign this form.";


            $name_of = "Name of Patient Representative:";
            $relationship_to = "Relationship to Patient :";
        } else {

            $title = "Information du patient et autorisation de traitement";
            $doc_date = "Fecha";
            $pat_info = "INFORMACION DEL PACIENTE #";
            $name = "Nombre";
            $address = "Direccion";
            $sex = "Sexo";
            $citysZ = "Ciudad, Zip:";
            $dob = "FDN";
            $phone = 'Teléfono';
            $homeph = "Teléfono";
            $age = "Edad";
            $date = "FDA";
            $workph = "Telé trabajo";
            $cellph = "Telé celular";
            $inj_title = "Injury Informacion";
            $inj_info = "INFORMACIÓN DE LESIÓN O ACCIDENTE";
            $post_sx = "Post-cirugia";
            $type = "Tipo";
            $sx_date = "FDC";
            $body_pts = "Partes del cuerpo";
            $refer_title = 'DOCTOR QUE REFIRIO AL PACIENTE INFORMACIÓN';
            $att_title = "INFORMACIÓN DEL ABOGADO";
            $emp_title = "INFORMACIÓN DE EMPLEO";
            $prm_insu = "INFORMACIÓN DE ASEGURANZA MEDICA";
            $sec_insu = "INFORMACIÓN SECUNDARIA DE ASEGURANZA MEDICA";
            $ins_name = "Asegurados";
            $rel_title = "Authorizacion para Proporcionar Informacion Personal y Asignacion de Beneficios";
            $auth_title = "Yo authorizo a West-Star Physical Therapy para que proporcione la informacion pertinente a este accidente en cuanto la aseguranza lo requiera. Yo authoizo que West-Star Physical Therapy reciba el pago directo de los beneficios de mi aseguranza por los servicios prestados.";
            $date_signed = "Fecha firmada";
            $patient = "Paciente";
            $hours = "Horas";

            $pain_info = "
INFORMACIÓN DEL DOLOR";
            $job = "INFORMACION DEL TRABAJO #";

            //value for 3 pdf page
            $job_title = "Cargo:";
            $job_desc = "Descripción de las funciones: ";
            $add_job_details = "DETALLES ADICIONALES DEL TRABAJO";
            $during = "  Durante un día típico de 8 horas, cuántas horas le hacen...";

            $emergency_room = "
Estuvo en la Sala de Emergencia?";
            $brief_injury = "Describa
brevemente su
accidente:";
            $emeregency_medical_fac = "Si no fue a un cuarto de emergencia, se presento en algún otro tipo de centro médico?";
            $xray = "Se tomaron rayos x?";
            $auto_accident = "Si fue un accidente de auto, pudo manejar el vehículo después del accidente?";
            $previous_injury = "Ha tenido algun accidente anterior en la misma parte del cuerpo?";
            $treted = "Si todavía esta reciviendo tratamiento medico por la misma lesión, favor llenar lo siguiente?";
            $being_treted = "Esta reciviendo tratamiento medico por la misma lesión";
            $ssn = "SSN";


            $sit = "Sentarse:";
            $stand = "Pararse:";
            $walk = "Caminar:";
            $drive = " Manejar:";
            $at_work = "Cuántas horas trabaja en un...";

            $day_shift = "Día Normal/Turno: ";
            $week = "Semana:";
            $at_wokr_spend = "En un dia normal, Cuántas horas hace el o siguiente...";

            $squatting = "El ponerse en cuclillas:";
            $stooping = "El inclinarse/que dobla:";
            $kneeling = "Arrodillamiento:";
            $reaching_up = "El alcanzar para arriba:";
            $reaching_out = "El alcanzar hacia fuera: ";
            $twisting = "El torcer:";
            $crawling = "Arrastre:";
            $stair_clm = "El subir de la escalones:";
            $ladder_clm = "El subir de la escalera:";
            $using_com = "Usando una computadora:";
            $using_tel = "Usando el teléfono:";
            $pushing = "Empujando:";
            $pulling = "Jalando:";
            $lifting_overhead = "Cargando cosas pesadas:";


            $constantly = "Constantemente";
            $Often = "A menudo";
            $Sometimes = "A veces";
            $Never = "Nunca";



            $at_work_lift = "En mi trabajo se requiere que levante... ";
            $lbs10 = "10 lbs o menos:";
            $lbs11 = "11 lbs a 25 lbs:";
            $lbs26 = "26 lbs a 50 lbs:";
            $lbs51 = "51 lbs a 75 lbs:";
            $lbs76 = "76 lbs a 100 lbs:";
            $lbs100 = "más de 100 lbs:";
            $at_work_inc = "Mi trabajo incluye..";
            $Repetitive1 = "Movimiento repetidor de manos:";
            $Repetitiv2 = "Movimiento repetidor de pies :";
            $power_grp = "El agarrar de la energía:";
            $precision = "Dirección de la precisión:";
            $balancing = "Equilibrio:";
            $use_computer_mouse = "Uso del ratón de la computadora/del cojín de tacto :";
            $timed_work = "Trabajo sincronizado para la eficacia :";
            $simultaneous = " Computadora simultánea y teléfono :";


            //value for 4 pdf page 

            $draw_location = "Marque la localización del dolor en los esquemas del cuerpo usando los marcadores siguientes.";

            $aches = "Dolores";
            $burning = "Arder";
            $nurnbness = "Entumecimiento";
            $pinsNeedles = "Fija el & Agujas";
            $stabbing = "Punalada";
            $other = "Otro";

            //value for 5 pdf page
            $waver_info = "FORMA de RENUNCIA de TRATAMIENTO";
            $waver_info_details = "YO, SOY DE LA EDAD LEGAL Y POR LA PRESENTE CERTIFICO QUE FUI A POR MI PROPIA VOLUNTAD Y LA DECISION DE RECIBIR TRATAMIENTO DE FISIOTERAPIA. COMPRENDO QUE YO PODRIA O NO TENER REFERENCIA DE UN MEDICO Y QUE OBTENIENDO FISIOTERAPIA ES MI TRATAMIENTO DE ELECCION. YO TAMBIEN COMPRENDO QUE SERE EVALUADO(A) POR UN TERAPISTA FISICO CON LICENCIA VALIDA Y CERTIFICADO Y QUE LA EVALUACION Y RECOMENDACION de EL TERAPEUTA SERAN EXPLICADAS A MI ANTES DEL TRATAMIENTO. COMPRENDO QUE EL SE COMUNICARA CON MI MEDICO PARA OBTENIR AUTORIZACION PARA MI TRATAMIENTO DE FISIOTERAPIA. YO TAMBIEN COMPRENDO QUE YO NO PUEDO RECIBIR TRATAMIENTO DE FISIOTERAPIA EN SIN LA AUTORIZACION FIRMADA MI MEDICO. ADEMAS, COMPRENDO QUE LA FISIOTERAPIA, PODRIA O NO GUARANTIZAR QUE MI CONDICION ACTUAL MEJORE.
            FzDQ";
            $if_min = "SI MENOR:";
            $name_of_parent = "El Nombre de Padre O Guardian del Paciente:";
            $relationship = "Relacion:";
            $patient_sign = "Firma:";
            $date = "Fecha:";
            $witnessed = "Testigo(a):";
            $name_of_staff = "Nombre de la Persona tomando los datos:";
            $sign_date = "Firma:";
            $waiver_title = "FORMA de RENUNCIA de TRATAMIENTO";


            $notice_head = "Notice of Privacy Practices";
            $privacy_info = "Aviso de Prácticas de Privacidad ";
            $page = "Pagina (1 de 3)";
            $notice_descibe = "ESTA NOTA DESCRIBE INFORMACION MEDICA SOBRE USTED que PUEDE SER UTILIZADA Y ES REVELADO Y COMO USTED PUEDE CONSEGUIR ACCESO A ESTA INFORMACION. REVISELO POR FAVOR CON CUIDADO";
            $uses_dis = "Usos y Revelaciones";

            $treatment_health = "El tratamiento: Su información de la salud puede ser utilizada por empleados de la clinica o compartida con otros profesionales de la asistencia médica para el propósito de evaluar su salud, diagnosticando condiciones médicas y proporcionar tratamiento. Por ejemplo, los resultados de examenes de laboratorio y procedimientos estarán disponibles en su historial médico para todos los profesionales de medicina que puedan proporcionar tratamiento o que pueden ser consultados por los empleados de la clinica.";

            $payment_health = "El pago: Su información de la salud puede ser utilizada para obtener es tu pagos de su plan de salud medico, de otras fuentes de alcance como una aseguranza de automóvil, o de las compañías de tarjeta de crédito que usted puede utilizar para pagar por servicios. Por ejemplo, su plan de salud puede solicitar y recibir información de fechas de servicio, servicios proveidos y la condición médica tratada.";

            $health_care_operation = "Las Operaciones de Asistencia médica: Su información de la salud puede ser utilizada para apoyar como sea necesario las actividades diarias y de la gestión de Weststar Physical Therapy. Por ejemplo, la información en los servicios que usted reciba pueden ser utilizados para mantener, presupuestar y cubrir actividades financieras para evaluar y promover calidad medica.";

            $law_enforcement = "La Aplicación de ley: su información de la salud puede ser enviada a agencias legales para mantener auditorías de gobierno e inspecciones, para facilitar investigaciones legales y para cumplir con el mandato de covertura del gobierno.";

            $public_health = "Anuncio De Salud Publica: Su información de la asistencia médica puede ser revelada a agencias sanitarias según exige la ley. Por ejemplo, nosotros somos requeridos a informar ciertas enfermedades transmisibles al departamento sanitarias del estado.";

            $other_uses = "Otros Usos y las Revelaciones Que Requieren Su Autorización: La distribucion de su información de salud o su uso para cualquier propósito ademas de los mentionados arriba requieren su autorización específica y en escrito. Sin embargo, su decisión de revocar autorización no afectará ningún uso ni la distribucion de información que ocurrió antes que usted nos notificara de su decisión de revocar su autorización.";




            $appointment_reminder = "Los Recordatorios de la cita: Su información de la salud será utilizada por nuestro personal para enviarle recordatorios de cita.";
            $page2 = "Paga (2 de 3)";
            $info_about_tratment = "La información sobre Tratamientos: Su información de la salud puede ser utilizada para enviarle información que usted puede encontrar interesante en el tratamiento y la gestión de su condición médica. De nuestra base de datos, nosotros también le podemos enviar información que describe Estrella sólo Occidental información relacionada que puede ser de interés a usted**";


            $dont_use_info = "Por Favor no utiliza mi información de la salud para los servicios sobredichos.";


            $individual_right = "Los Derechos individuales: Usted tiene ciertos derechos bajo los estándares federales de intimidad.
            Estos incluyen:";


            $individual_right1 = "Estos incluyen:
            El derecho de solicitar restricciones en el uso y la revelación de su información protegida de";

            $individual_right2 = "Estos incluyen:
            El derecho de solicitar restricciones en el uso y la revelación de su información protegida de asistencia médica; ";


            $individual_right3 = "El derecho de recibir comunicaciones confidenciales con respecto a su condición y el tratamiento médica;";

            $individual_right4 = "El derecho de inspeccionar y copiar su información protegida de la salud;";


            $individual_right5 = "El derecho de enmendar o someterse correcciones a su información protegida de la asistencia médica;";





            $individual_right6 = "El derecho de recibir una contabilidad de cómo y a quien su información protegida de salud tiene Fue
            revelado; El derecho de recibir una copia impresa de esta nota.";



            $west_star = "Los Deberes occidentales de WestStar: Somos requeridos por la ley a mantener que la intimidad de su información protegida de la salud y para proporcionarle con esta nota de prácticas de intimidad.";


            $abide = "Nosotros también somos requeridos a respetar las políticas de intimidad y prácticas que son resumidas en esta nota.";

            $right_to_reserve = "Correcto Revisar las Prácticas de Intimidad: Como permitido por la ley, nosotros reservamos el derecho de enmendar para modificar nuestras políticas de intimidad y prácticas. Estos cambios en nuestras políticas y prácticas pueden ser requeridos por cambios en federal y las leyes de estado y regulaciones. Sobre la petición, nosotros le proporcionaremos con la nota más recientemente revisada en cualquier visita de la oficina. El revisa políticas y prácticas serán aplicadas a toda información protegida de la salud que mantenemos.";


            $request_to_inspect = "Las peticiones para Inspeccionar Información Protegida de Salud: Usted puede inspeccionar generalmente o puede copiar la información protegida de la salud que mantenemos. Como permitido por Regulaciones Federales nosotros requerimos que peticiones a inspeccionar o copiar información protegida de salud es sometida en la escritura. Usted puede obtener una forma para solicitar acceso a sus registros contactando nuestra oficina corporativa. Su petición será revisada y será aprobada generalmente a menos que hay razones legales o médicas de negar la petición.";


            $complaints = "Las quejas: Si usted querría someterse un comentario o la queja acerca de nuestras prácticas de intimidad, usted puede hacer así enviando una carta que resume sus preocupaciones a:";


            $adress1 = "West Star Physical Therapy";
            $address2 = "5400 Orange Street";

            $address2 = "Suite #215";
            $address2 = "Cypress, CA 90630";


            $belive_your_priv = "Si usted cree que sus derechos de intimidad han sido violados, usted debe llamar el asunto a nuestra atención enviando una carta que describe la causa de preocupación a la misma dirección. Usted no será penalizado ni de otro modo será vengado en contra para archivar una queja.";

            $contact_person = "Contacte a Persona: El nombre y la dirección de la persona usted puede contactar para la información adicional con respecto a prácticas de intimidad es:";

            $west = "West Star Physical Therapy";


            $address5 = "PO Box 6209";
            $address6 = "Garden Grove, CA 92846";


            $effective_date = "La Fecha de vigencia: Esta nota es efectiva al el 7 de mayo de 2003";
        }



        $html = '

        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
            <tr>
                <td>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 30%;">
                                <img src="images/logos.JPG" alt="logo" style="max-width: 150px">
                            </td>
                            <td style="text-align: right; width: 70%;">
                                <h3 style="font-size: 18px;  margin: 0;">' . $title . '</h3>
                                <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px; "> ' . $pat_info . '</p>
                            </td>
                            <td style="text-align: right; border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0;color:  #7d7d7d;font-weight: 600; font-size: 14px;"> ' . $data["case"]["clinic"] . '</p>
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;  height: 23px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;"">' . $name . ' :</label>
                                <input type=" text" class="inputCC" style="width: 70%;float: right; margin-right: 11px; margin-top: 2px; height: 18px; font-size: 12px; padding-left: 4px;"  value="' . $data["patient"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%;   height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $ssn . ' :</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;"  value="' .  $dataSnn . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;  height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 9px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["address"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $sex . ' :</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["sex"] . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;  height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $citysZ . ' </label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 9px; height: 15px; font-size: 12px; padding-left: 4px;"  value="' . $data["patient"]["city"]  . $data["patient"]["state"]  . $data["patient"]["zip"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;  ">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 10px;">' . $dob . ' :</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["birthdate"] . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $homeph . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 9px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["phonehome"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $age . ':</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["age"] . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $workph . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px;" value="' . $data["patient"]["phonework"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Email:</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $cellph . ':</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px;" value="' . $data["patient"]["phonecell"] . '">
                            </td>
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;"></label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;">
                            </td>
                        </tr>

                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d; height: 22px;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $pat_info . '</p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $date . ':</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px;" value="' . $data["case"]["injurydate"] . '">
                            </td>
                            <td style="text-align: start; width: 50%;  height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $post_sx . ' :</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["case"]["postsurgical"] . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $type . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px;" value="' . $data["case"]["casetype"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $sx_date . ':</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["case"]["surgerydate"] . '" >
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;"> ' . $refer_title . '  </p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%; float: right; margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["doctor"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $body_pts . ' :</label>
                                <input type="text" class="inputCC" style="width: 70%; float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["rx"]["bodypart"] . '">

                            </td>

                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%; float: right; margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["doctor"]["address"] . '">
                            </td>

                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $citysZ . ' </label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right; margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["doctor"]["city"]  . $data["doctor"]["state"]  . $data["doctor"]["zip"] . '">
                            </td>

                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $phone . ' :
                                </label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["doctor"]["phone"] . '"> 
                            </td>
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Dx:</label>
                                <input type="text" class="inputCC" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;"  value="' . $data["rx"]["diagnosis"] . '">
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $att_title . '</p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px; "  value="' . $data["attorney"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["attorney"]["address"] . '">
                            </td>
                            
                        </tr>
                        
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $citysZ . ' </label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $attorneyAddress . '">
                            </td>
                           <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $phone . ' :
                                </label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["attorney"]["phone"] . '">
                            </td>
                        </tr>
                       
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $emp_title . ' :</p>
                            </td>
                            <td style="text-align: start; border-bottom: 1px solid #7d7d7d;">

                            </td>
                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["employer"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["employer"]["address"] . '">
                            </td>
                        </tr>
                        
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $citysZ . '</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $empAddress . '">
                            </td>
                           <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $phone . ' :</label>
                                <input type="text" class="inputCC" style=" width: 70%;float: right;margin-right: 10px; height: 15px; font-size: 12px; padding-left: 4px;"  value="' . $data["employer"]["phone"] . '">
                            </td>
                        </tr>
                        

                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $prm_insu . '</p>
                            </td>
                            <td style="text-align: start; border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $sec_insu . '</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["insurance1"]["name"] . '">
                               
                                </td>
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" style="width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="' . $data["insurance2"]["name"] . '">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; margin: 1px 0; ">
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                 
                            <td style="text-align: start; width: 50%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" style=" width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;  margin: 1px 0;">
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;"></label>
                                
                            </td>
                            <td style="text-align: start; width: 50%;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;"></label>
                              
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Adj/Ph#:</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Adj/Ph#:</label>
                                <input type="text" style=" width: 70%;float: right;height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $type . ' :</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $type . ' :</label>
                                <input type="text" style=" width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Ins ' . $name . ' :</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Ins ' . $name . ' :</label>
                                <input type="text" style=" width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">

                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Pol#/Clm#:</label>
                                <input type="text" style=" width: 70%;float: right;margin-right: 12px; height: 15px; font-size: 12px; padding-left: 4px;" value="">

                            </td>
                            <td style="text-align: start; width: 50%;height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">Pol#/Clm#:</label>
                                <input type="text" style=" width: 70%;float: right; height: 15px; font-size: 12px; padding-left: 4px;" value="">
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                    </table>

            <tr>
                <td style="text-align: start;border-bottom: 1px solid #7d7d7d; width: 100%;">
                    <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">' . $rel_title . '
                    </p>
                </td>
                <td style="text-align: start; border-bottom: 1px solid #7d7d7d;">
                </td>
            </tr>
            <tr>
                <td style=" width: 100%;">
                    <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;">
                    ' . $auth_title . '
                    </p>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="height: 10px;">
                </td>
                <td style="height: 10px;">
                </td>
            </tr>
            <tr style="vertical-align: middle;">
                <td style="text-align: start;  padding-right: 20px;">
                </td>
                <td style="text-align: start;  padding-left: 20px;">
                    <p style="margin: 0; font-size: 14px; font-weight: 600;">
                       ' . $currentDate . '
                    </p>
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td style="text-align: start; padding-right: 20px;width: 50%;">
                    <p style="margin: 0; border-top: 1px solid #7d7d7d; padding-top: 5px;">' . $data["patient"]["name"] . '
                    </p>
                </td>
                <td style="text-align: start; padding-left: 20px;width: 50%;">
                    <p style="margin: 0; border-top: 1px solid #7d7d7d; padding-top: 5px;">' . $date_signed . '
                    </p>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>

        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
        <tr>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start; width: 30%; line-height: 1.4;">
                            <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                        </td>
                        <td style="text-align: right; width: 70%;">
                        <h3 style="font-size: 18px;  margin: 0;">' . $job . '</h3>
                        <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                    </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                            <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height: 1.8; text-transform:uppercase;">' . $patient . ' #</p>
                        </td>
                        
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                        <td style="height: 10px;">
                        </td>
                        <td style="height: 10px;">
                        </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                            <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                        </td>
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                            <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"  value="' .  $dataSnn . '">
                        </td>
                    </tr>
                    
        
                    <tr>
                        <td style="height: 30px;">
                        </td>
                        <td style="height: 30px;">
                        </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                            <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $job . '</p>
                        </td>
                        <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                    </tr>
                    <tr>
                        <td style="height: 10px;">
                        </td>
                        <td style="height: 10px;">
                        </td>
                    </tr>
                    <tr style="vertical-align: middle; ">
                        <td colspan="2" style="text-align: start; width: 100%; height: 30px">
                            <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $job_title . '</label>
                            <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;">
                        </td>
                        
                    </tr>
                    <tr style="vertical-align: middle; ">
                        <td colspan="2" style="text-align: start; width: 100%; height: 30px">
                            <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $job_desc . '</label>
                            <textarea style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 40px; resize:none;font-size: 12px; padding-left: 4px;"></textarea>
                        </td>
                        
                    </tr>
                    <tr>
                       <td colspan="2" style="height: 15px;">
                       </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                            <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $add_job_details . '</p>
                        </td>
                        <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                    </tr>
                    <tr>
                       <td colspan="2" style="height: 15px;">
                    &nbsp;
                       </td>
                    </tr>

                    
              
                    <tr style="vertical-align: top;">
                        <td  style="text-align: start; width: 100%;">
                            <p style="margin: 0; color: #7d7d7d; font-size: 13px; line-height:1.5">' . $during . '</p>
                            <table border="0" width="100%" cellspacing="0">
                               <tr>
                                    <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $sit . '</td>
                                    <td width="30%" style="height: 20px;">
                                      <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                    </td> 
                                    <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>

                               <tr>
                                    <td width="30%" style="color: #7d7d7d; font-size: 13px;">' . $stand . '</td>
                                    <td width="20%" style="height: 20px;">
                                        <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height: 8px;">
                                    </td>
                                    <td width="50%" style="color: #7d7d7d; font-size: 13px; text-align: left;">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                    <td width="30%" style="color: #7d7d7d; font-size: 13px;">' . $walk . '</td>
                                    <td  width="20%" style="height: 20px;">
                                        <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;">
                                    </td>
                                    <td width="50%" style="color: #7d7d7d; font-size: 13px; text-align: left;">' . $hours . '</td>
                               </tr>
                          
                               <tr>
                                    <td width="30%" style="color: #7d7d7d; font-size: 13px;">' . $drive . '</td>
                                    <td width="20%" style="height: 20px;">
                                        <input  type="text" style="width: 80%; float: right; border: 1px solid #7d7d7d; border-radius:4px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 4px 2px 2px 2px; height: 10px;">
                                    </td>
                                    <td width="50%" style="color: #7d7d7d; font-size: 13px; text-align: left;">' . $hours . '</td>
                               </tr>
                               <tr>
                                  <td colspan="4" style="height: 3px;">
                                  </td>
                                </tr>
                               <tr>
                                  <td colspan="3" style="color: #7d7d7d; font-size: 15px;">' . $at_work . '
                                  </td>
                                </tr>
                               <tr>
                                  <td colspan="4" style="height: 3px;">
                                  </td>
                                </tr>
                               <tr>
                                    <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $day_shift . '</td>

                                    <td width="30%" style="height: 20px;">
                                        <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                    </td>

                                    <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                               
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 13px;">' . $week . '</td>
                                 <td  width="20%" style="height: 20px;">
                                    <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height: 8px;">
                                 </td>
                                 <td width="50%" style="color: #7d7d7d; font-size: 13px; text-align: left;">' . $hours . '</td>
                               </tr>
                           </table>
                        </td>
                         <td  style="text-align: start; width: 100%;">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                                  <td colspan="3" style="color: #7d7d7d; font-size: 13px;">' . $at_wokr_spend . '
                                  </td>
                                </tr>
                               
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $squatting . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;">
                                </td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                               
                               
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $stooping . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;">
                                </td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>

                              

                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $kneeling . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $reaching_up . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $reaching_out . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $twisting . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $crawling . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                           
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $stair_clm . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                              
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $ladder_clm . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                               
                               <tr>
                               
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $using_com . ' </td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>

                               
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $using_tel . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                             
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $pushing . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                             
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $pulling . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                             
                               <tr>
                                <td width="30%" style="color: #7d7d7d; font-size: 12px; line-height:13px">' . $lifting_overhead . '</td>
                                <td width="20%" style="height: 22px;">
                                   <input  type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; margin: 2px; height:10px;"></td>
                                <td width="20%" style="color: #7d7d7d; font-size: 12px; text-align: left;line-height:13px">' . $hours . '</td>
                               </tr>
                               
                           </table>
        
                        </td>
                        
                    </tr>

                    

                 
                    <tr>
                        <td colspan="2">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                                  <td colspan="5" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                  <td width="40%" style="color: #7d7d7d; font-size: 15px;">' . $at_work_lift . '
                                  </td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $constantly . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Often . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Sometimes . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Never . '</td>
                                  </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs10 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs11 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs26 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs51 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs76 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $lbs100 . '</td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                                <td align="center">
                                
                                     <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">
                                </td>
                               </tr>
                               
                               
                               <tr>
                                  <td colspan="5" style="height: 15px;">
                                  </td>
                                </tr>
                               <tr>
                                  <td width="40%" style="color: #7d7d7d; font-size: 15px;">' . $at_work_inc . '
                                  </td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $constantly . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Often . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Sometimes . '</td>
                                  <td align="center" width="15%" style="color: #7d7d7d; font-size: 12px;">' . $Never . '</td>
                                  </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $Repetitive1 . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $Repetitiv2 . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $power_grp . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $precision . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $balancing . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $use_computer_mouse . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;">' . $timed_work . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               <tr>
                                  <td colspan="3" style="height: 5px;">
                                  </td>
                                  </tr>
                               <tr>
                                <td style="color: #7d7d7d; font-size: 12px;"> ' . $simultaneous . '</td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                                <td align="center">
                                   <input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;border: 1px solid #7d7d7d; border-radius:4px;  margin: 2px; height: 8px;">     
                                </td>
                               </tr>
                               
                               
                           </table>
                        </td>
                    </tr>
                    
                   
                    
                    
                    <tr>
                        <td style="height: 40px;">
                        </td>
                        <td style="height: 40px;">
                        </td>
                    </tr>
                </table>
        
        
        
        </td>
        </tr>
        </table>
        

      
        
        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
            <tr>
                <td>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 30%; line-height: 1.4;">
                                <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                            </td>
                         
                            <td style="text-align: right; width: 70%;">
                            <h3 style="font-size: 18px;  margin: 0;">' . $inj_title . '</h3>
                            <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                        </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                            </td>
                            
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse;">
                    				<tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <span style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $name . ' :</span>
                                <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                                <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                            </td>
                        </tr>
                        

                        <tr>
                            <td style="height: 30px;">
                            </td>
                            <td style="height: 30px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $inj_info . ' #</p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td colspan="2" style="text-align: start; width: 100%; height: 100px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $brief_injury . ' :</label>
                                <textarea style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height:80px; resize:none;"></textarea>
                            </td>
                            
                        </tr>
                        <tr>
                           <td colspan="2" style="height: 15px;">
                           </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start; width: 100%;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                    <td width="80%">&nbsp;</td>
                                    <td width="10%" align="center" style="color: #7d7d7d; font-weight:600; font-size: 15px;">Yes</td>
                                    <td width="10%" align="center" style="color: #7d7d7d; font-weight:600; font-size: 15px;">No</td>
                                   </tr>
                                   <tr>
                                      <td colspan="3" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d; font-size: 15px; height: 30px;">' . $emergency_room . '</td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                
                                   <tr>
                                      <td colspan="4" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d;font-size: 15px; height: 30px;"> ' . $emeregency_medical_fac . '</td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                   <tr>
                                      <td colspan="4" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d;font-size: 15px; height: 30px;">' . $xray . '</td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;  height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                   <tr>
                                      <td colspan="4" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d;font-size: 15px; height: 30px;">' . $auto_accident . '</td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                   <tr>
                                      <td colspan="4" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d;font-size: 15px; height: 30px;">' . $previous_injury . '</td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                   <tr>
                                      <td colspan="4" style="height: 5px;">
                                      </td>
                                  	</tr>
                                   <tr>
                                    <td style="color: #7d7d7d; font-size: 15px; height: 30px;">' . $being_treted . ' </td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                    <td><input type="text" style="width: 80%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;"></td>
                                   </tr>
                                   
                               </table>

                            </td>
                            
                        </tr>
                        <tr>
                            <td style="height: 30px;">
                            </td>
                            <td style="height: 30px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px; line-height:1.8">' . $treted . ' </p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td colspan="2" style="text-align: start; width: 100%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $name . ' :</label>
                                <input type="text" style=" width: 70%; float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 15px font-size: 12px; padding-left: 4px;"> 
                            </td>

                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start; width: 100%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $address . ' :</label>
                                <input type="text" style=" width: 70%; float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 15px font-size: 12px; padding-left: 4px;">
                            </td>

                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start; width: 100%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px; ">' . $citysZ . '</label>
                                <input type="text" style=" width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 15px font-size: 12px; padding-left: 4px;">
                            </td>

                        </tr>
                        <tr>
                            <td style="height: 5px;">
                            </td>
                            <td style="height: 5px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start; width: 100%; height: 22px;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;">' . $phone . '
                                </label>
                                <input type="text" style=" width: 70%;float: right;border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 15px font-size: 12px; padding-left: 4px;">
                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td style="height: 40px;">
                            </td>
                            <td style="height: 40px;">
                            </td>
                        </tr>
                    </table>
     
        </td>
        </tr>
        </table>




        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
            <tr>
                <td>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 30%; line-height: 1.4;">
                                <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                            </td>
                     
                            <td style="text-align: right; width: 70%;">
                            <h3 style="font-size: 18px;  margin: 0;">' .  $pain_info . '</h3>
                            <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                        </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                            </td>
                            
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse;">
                    				<tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>




                        <tr style="vertical-align: middle;">
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <span style="color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                            <input type="text" style="width: 70%; float: right; margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                        </td>
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <label style="color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $ssn . ':</label>
                            <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                        </td>
                    </tr>

                        <tr>
                            <td style="height: 30px;">
                            </td>
                            <td style="height: 30px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $pain_info . ' #</p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td colspan="2" style="text-align: start; width: 100%;">
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $draw_location . '</p>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                     <td colspan="2" style="height: 15px;">
                                     </td>
                                 </tr>
                                  <tr>
                                   <td width="30%" valign="top">
                                   			<p style="margin: 0; color: #7d7d7d; font-size: 15px; border:1px solid #7d7d7d; line-height:1.2; padding:5px 10px;">
                                      		<span style="display:block">A = ' . $aches . 'ches</span>
                                        <span style="display:block">B = ' . $burning . '</span>
                                        <span style="display:block">N = ' . $nurnbness . '</span>
                                        <span style="display:block">P = ' . $pinsNeedles . '</span>
                                        <span style="display:block">S = ' . $stabbing . '</span>
                                        <span style="display:block">0 = ' . $other . '</span>
                                      </p>
                                   </td>
                                   <td width="70%" align="center"><img src="images/man.jpg" alt="" style="max-width: 550px"></td>
                                  </tr>
                                </table>

                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td style="height: 40px;">
                            </td>
                            <td style="height: 40px;">
                            </td>
                        </tr>
                    </table>

            

        

        </td>
        </tr>
        </table>

















        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
            <tr>
                <td>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 30%; line-height: 1.4;">
                                <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                            </td>
                          
                            <td style="text-align: right; width: 70%;">
                            <h3 style="font-size: 18px;  margin: 0;">' .  $waiver_title . '</h3>
                            <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                        </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                            </td>
                            
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse;">
                    				<tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                                <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                                <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px;font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                            </td>
                        </tr>
                        

                        <tr>
                            <td style="height: 30px;">
                            </td>
                            <td style="height: 30px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $waver_info . ' </p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d; height: 18px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td colspan="2" style="text-align: start; width: 100%;">
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $waver_info_details . ' </p>
                                <table width="92%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                     <td colspan="2" style="height: 15px;">
                                     </td>
                                 </tr>
                                 <tr style="vertical-align: middle;">
                                      <td style="text-align: start;">
                                          <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $if_min . '
                                       
                                          </p>
                                      </td>
                                      <td style="text-align: end;"></td>
                                  </tr>
                                  <tr>
                                      <td style="height: 10px;">
                                      </td>
                                      <td style="height: 10px;">
                                      </td>
                                  </tr>
                                  <tr>
                                   <td width="50%" valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">   ' . $name_of_parent . ' 
                                               </label>
                                   </td>
                                   <td width="50%" align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 70%;float: right; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $relationship . '
                                            </label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">   ' . $patient_sign . '
                                           </label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">    ' . $date . '
                                              </label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;"> ' . $witnessed . ' 
                                             </label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">  ' . $name_of_staff . '
                                               </label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                   <td valign="bottom" align="right">
                                   			<label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $sign_date . '</label>
                                   </td>
                                   <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   			<input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                   </td>
                                  </tr>
                                  <tr>
                                  <td valign="bottom" align="right">
                                              <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $date . '</label>
                                  </td>
                                  <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                              <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                                  </td>
                                 </tr>
                                </table>

                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td style="height: 40px;">
                            </td>
                            <td style="height: 40px;">
                            </td>
                        </tr>
                    </table>

            

        

        </td>
        </tr>
        </table>












        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
            <tr>
                <td>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 30%; line-height: 1.4;">
                                <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                            </td>
        

                            <td style="text-align: right; width: 70%;">
                            <h3 style="font-size: 18px;  margin: 0;">Notice of Privacy Practices</h3>
                            <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                        </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                            </td>
                            
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse;">
                    				<tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                                <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                            </td>
                            <td style="text-align: start; width: 50%; line-height: 1.4;">
                                <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                                <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                            </td>
                        </tr>
                        

                        <tr>
                            <td style="height: 30px;">
                            </td>
                            <td style="height: 30px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle;">
                            <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                                <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $privacy_info . ' <span style="text-transform: initial; font-weight: normal;">' . $page . '</span></p>
                            </td>
                            <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                        </tr>
                        <tr>
                            <td style="height: 10px;">
                            </td>
                            <td style="height: 10px;">
                            </td>
                        </tr>
                        <tr style="vertical-align: middle; ">
                            <td colspan="2" style="text-align: start; width: 100%;">
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $treatment_health . ' </p>
                                <br>
																																<p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $uses_dis . '</p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $treatment_health . ' </p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $payment_health . ' </p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $health_care_operation . ' </p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $law_enforcement . '</p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $public_health . '</p><br>
                                <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $other_uses . ' </p>
                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td style="height: 40px;">
                            </td>
                            <td style="height: 40px;">
                            </td>
                        </tr>
                    </table>


        

        </td>
        </tr>
        </table>




        <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
        <tr>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start; width: 30%; line-height: 1.4;">
                            <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                        </td>
                        <td style="text-align: right; width: 70%;">
                        <h3 style="font-size: 18px;  margin: 0;">Notice of Privacy Practices</h3>
                        <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                    </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                            <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                        </td>
                        
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                        <td style="height: 10px;">
                        </td>
                        <td style="height: 10px;">
                        </td>
                    </tr>
                    
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                            <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                        </td>
                        <td style="text-align: start; width: 50%; line-height: 1.4;">
                            <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                            <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                        </td>
                    </tr>
                    

                    <tr>
                        <td style="height: 30px;">
                        </td>
                        <td style="height: 30px;">
                        </td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                            <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $privacy_info . ' <span style="text-transform: initial; font-weight: normal;">' . $page2 . '</span></p>
                        </td>
                        <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                    </tr>
                    <tr>
                        <td style="height: 10px;">
                        </td>
                        <td style="height: 10px;">
                        </td>
                    </tr>
                    <tr style="vertical-align: middle; ">
                        <td colspan="2" style="text-align: start; width: 100%;">
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $appointment_reminder . '</p>
                            <br>
                                                                                                                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $info_about_tratment . ' </p><br>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;"><input type="text" style="width: 100px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px;  height: 18px;"> ' . $dont_use_info . ' </p><br>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right . ' </p><br>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right1 . '</p>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right2 . ' </p>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right3 . ' </p>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right4 . '</p>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right5 . ' </p>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $individual_right6 . ' </p><br>
                            
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $west_star . ' </p><br>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $abide . '</p><br>
                            <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $right_to_reserve . ' </p>
                            
                        </td>
                        
                    </tr>
                    
                    <tr>
                        <td style="height: 40px;">
                        </td>
                        <td style="height: 40px;">
                        </td>
                    </tr>
                </table>

        


    </td>
    </tr>
    </table>
















    <table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
    <tr>
        <td>
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="vertical-align: middle;">
                    <td style="text-align: start; width: 30%; line-height: 1.4;">
                        <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                    </td>
                    <td style="text-align: right; width: 70%;">
                    <h3 style="font-size: 18px;  margin: 0;">Notice of Privacy Practices</h3>
                    <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
                </td>
                </tr>
                <tr style="vertical-align: middle;">
                    <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                        <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                    </td>
                    
                </tr>
            </table>
            <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                    <td style="height: 10px;">
                    </td>
                    <td style="height: 10px;">
                    </td>
                </tr>
                
                <tr style="vertical-align: middle;">
                    <td style="text-align: start; width: 50%; line-height: 1.4;">
                        <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                        <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                    </td>
                    <td style="text-align: start; width: 50%; line-height: 1.4;">
                        <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                        <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                    </td>
                </tr>
                

                <tr>
                    <td style="height: 30px;">
                    </td>
                    <td style="height: 30px;">
                    </td>
                </tr>
                <tr style="vertical-align: middle;">
                    <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                        <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">' . $privacy_info . '<span style="text-transform: initial; font-weight: normal;">Page (3 of 3)</span></p>
                    </td>
                    <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
                </tr>
                <tr>
                    <td style="height: 10px;">
                    </td>
                    <td style="height: 10px;">
                    </td>
                </tr>
                <tr style="vertical-align: middle; ">
                    <td colspan="2" style="text-align: start; width: 100%;">
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;"> ' . $request_to_inspect . ' </p>
                        <br>
                                                                                                                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $complaints . ' </p><br>
                        
              
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $west . '<br>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">PO BOX 6209</p>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">Garden Grove, CA 92846</p>
                        <br>
                        
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $belive_your_priv . '</p><br>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $contact_person . '</p><br>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $west . '<br>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">PO BOX 6209</p>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">Garden Grove, CA 92846</p>


                        <span style="color:#000; font-weight:bold;">' . $adress5 . '</span><br><span style="color:#000; font-weight:bold;">' . $adress6 . '</span>
                        </p><br>
                        <p style="margin: 0; color: #7d7d7d; font-size: 15px;">' . $effective_date . ' </p>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    <td style="height: 40px;">
                    </td>
                    <td style="height: 40px;">
                    </td>
                </tr>
            </table>

</td>
</tr>
</table>










<table style="width: 100%; font-family:"Arial", Helvetica, sans-serif;" bgcolor="#fff">
<tr>
    <td>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="vertical-align: middle;">
                <td style="text-align: start; width: 30%; line-height: 1.4;">
                    <img src="images/logos.JPG" alt="logo" style="max-width: 250px">
                </td>
                <td style="text-align: right; width: 70%;">
                <h3 style="font-size: 18px;  margin: 0;">Notice of Privacy Practices</h3>
                <p style="margin: 5px 0 0;">' . $doc_date . ' <span>' . $currentDate . '</span></p>
            </td>
            </tr>
            <tr style="vertical-align: middle;">
                <td colspan="2" style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                    <p style="margin: 0; color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.8; text-transform:uppercase;">PATIENT #</p>
                </td>
                
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                <td style="height: 10px;">
                </td>
                <td style="height: 10px;">
                </td>
            </tr>
            
            <tr style="vertical-align: middle;">
                <td style="text-align: start; width: 50%; line-height: 1.4;">
                    <span style="  color: #7d7d7d;font-weight: 600;  font-size: 14px;  line-height: 1.4;">' . $name . ' :</span>
                    <input type="text" style="width: 70%;float: right;margin-right: 10px; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' . $data["patient"]["name"] . '">
                </td>
                <td style="text-align: start; width: 50%; line-height: 1.4;">
                    <label style="  color: #7d7d7d;font-weight: 600;  font-size: 12px;  line-height: 1.4;">' . $ssn . ':</label>
                    <input type="text" style="width: 70%;float: right; border: 1px solid #7d7d7d; border-radius:4px; padding: 5px; height: 18px; font-size: 12px; padding-left: 4px;" value="' .  $dataSnn . '">
                </td>
            </tr>
            

            <tr>
                <td style="height: 30px;">
                </td>
                <td style="height: 30px;">
                </td>
            </tr>
            <tr style="vertical-align: middle;">
                <td style="text-align: start;border-bottom: 1px solid #7d7d7d;">
                    <p style="margin: 0; color: #7d7d7d;font-weight: 600; font-size: 14px; line-height:1.8; text-transform:uppercase;">PRIVACY ACKNOWLEDGMENT INFORMATION</p>
                </td>
                <td style="text-align: end; border-bottom: 1px solid #7d7d7d;"></td>
            </tr>
            <tr>
                <td style="height: 10px;">
                </td>
                <td style="height: 10px;">
                </td>
            </tr>
            <tr style="vertical-align: middle; ">
                <td colspan="2" style="text-align: start; width: 100%;">
                    <p style="margin: 0; color: #7d7d7d; font-size: 15px; text-align:center; line-height:1.4;">' . $ack . '</p>
                    <p style="margin: 0; color: #7d7d7d; font-size: 15px; line-height:1.4;">' . $ack_recived . ' </p>
                    <table width="94%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                         <td colspan="2" style="height: 15px;">
                         </td>
                     </tr>
                     
                      <tr>
                          <td style="height: 10px;">
                          </td>
                          <td style="height: 10px;">
                          </td>
                      </tr>
                      <tr>
                       <td width="50%" valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $patient . ' :</label>
                       </td>
                       <td width="50%" align="left" valign="top">
                                   
                       </td>
                      </tr>
                      <tr>
                       <td valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;"> ' . $sign_date . '</label>
                       </td>
                       <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                       </td>
                      </tr>
                      <tr>
                       <td valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $date . '</label>
                       </td>
                       <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                       </td>
                      </tr>
                      <tr>
                         <td colspan="2" style="height: 15px;">
                         </td>
                     </tr>
                    </table>
                    
                    <p style="margin: 0; color: #7d7d7d; font-size: 15px; line-height:1.4;">' . $patient_representative . '</p>
                    <table width="94%" border="0" cellspacing="0" cellpadding="0">
                      
                     
                      <tr>
                          <td style="height: 10px;">
                          </td>
                          <td style="height: 10px;">
                          </td>
                      </tr>
                      <tr>
                       <td width="50%" valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $name_of . '</label>
                       </td>
                       <td width="50%" align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   
                       </td>
                      </tr>
                      <tr>
                       <td valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $relationship_to . '</label>
                       </td>
                       <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                       </td>
                      </tr>
                      <tr>
                       <td valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $sign_date . '</label>
                       </td>
                       <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px; ">
                       </td>
                      </tr>
                      <tr>
                       <td valign="bottom" align="right">
                                   <label style="margin: 0; color: #7d7d7d; font-size: 14px;">' . $date . '</label>
                       </td>
                       <td align="left" valign="top" style="border-bottom: 1px solid #7d7d7d;">
                                   <input type="text" style="width: 100%; border: none; border-radius:0; padding: 5px; font-size: 12px; padding-left: 4px;">
                       </td>
                      </tr>
                      
                    </table>

                </td>
                
            </tr>
            
            <tr>
                <td style="height: 40px;">
                </td>
                <td style="height: 40px;">
                </td>
            </tr>
        </table>



</td>
</tr>
</table>


  ';

        $html .= '<style>
  .inputCC {
    display: inline;
    white-space: nowrap;
    border: 1px solid #999;
  }

  .inputCC:before {
    content: attr(value);
  }
</style>';

        $dompdf = new Dompdf();

        // $html = "hello";
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrate');
        $dompdf->render();
        $output = $dompdf->output();
        $pdf_path = $_SERVER['DOCUMENT_ROOT'] . '/modules/patient/Reports/userPdf/' . $id . 'file.pdf';
        $pdf_url = '/modules/patient/Reports/userPdf/' . $id . 'file.pdf';
        if (file_put_contents($pdf_path, $output)) {
            echo $pdf_url;
            die;
        }
    }
}
