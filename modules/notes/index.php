<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
dump("session button",$_SESSION['button']);
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>
<?php
$button = $_SESSION['button'];

$id = $_SESSION['id'];
if(!empty($_POST['noid'])) {
	$idkey='noid';
	$id=$_POST['noid'];
}

switch($button):
	case 'Back':
	case 'Cancel':
		$searchNotesSaved = getformvars('notes', 'search');
		$sortNotesSaved = getformvars('notes', 'searchResults');
		$_POST['formSubmit']="1";
		break;
	case 'Confirm Add':
		require_once('notesSQLFunctions.php');
		notesAddNote(NULL);
		unset($_SESSION['button']);
		break;
	case 'Confirm Update':
		require_once('notesSQLFunctions.php');
		notesEditNote($id);
		unset($_SESSION['button']);
		break;
	case 'Confirm Delete':
		require_once('notesSQLFunctions.php');
		notesDeleteNote($id);
		unset($_SESSION['button']);
		break;
endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):
	case 'Add':
		require_once('notesAddScript.php');
		noteAddNote($id);
		unset($_SESSION['button']);
		break;
	case 'Display':
		require_once('notesDisplayScript.php');
		noteDisplayNote($id);
		unset($_SESSION['button']);
		break;
	case 'Edit':
		require_once('notesEditScript.php');
		noteEditNote($id);
		unset($_SESSION['button']);
		break;
	case 'Delete':
		require_once('notesDeleteScript.php');
		noteDeleteNote($id);
		unset($_SESSION['button']);
		break;
	default:
		require_once('notesSearchForm.php');
		require_once('notesSearchResultsForm.php');
		break;
endswitch;
?>