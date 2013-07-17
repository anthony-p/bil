<?php

// Menu
define("EWRPT_MENUBAR_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EWRPT_MENUBAR_SUBMENU_CLASSNAME", "", TRUE);
?>
<?php

/**
 * Menu class
 */

class crMenu {
	var $Id;
	var $IsRoot = FALSE;
	var $NoItem = NULL;
	var $ItemData = array();

	function crMenu($id) {
		$this->Id = $id;
	}

	// Add a menu item
	function AddMenuItem($id, $text, $url, $parentid, $src, $target, $allowed = TRUE) {
		$item = new crMenuItem($id, $text, $url, $parentid, $src, $target, $allowed);

		// Fire MenuItem_Adding event
		if (function_exists("MenuItem_Adding") && !MenuItem_Adding($item))
			return;
		if ($item->ParentId < 0) {
			$this->AddItem($item);
		} else {
			if ($oParentMenu =& $this->FindItem($item->ParentId))
				$oParentMenu->AddItem($item);
		}
	}

	// Add item to internal array
	function AddItem($item) {
		$this->ItemData[] = $item;
	}

	// Find item
	function &FindItem($id) {
		$cnt = count($this->ItemData);
		for ($i = 0; $i < $cnt; $i++) {
			$item =& $this->ItemData[$i];
			if ($item->Id == $id) {
				return $item;
			} elseif (!is_null($item->SubMenu)) {
				if ($subitem =& $item->SubMenu->FindItem($id))
					return $subitem;
			}
		}
		return $this->NoItem;
	}

	// Check if a menu item should be shown
	function RenderItem($item) {
		if (!is_null($item->SubMenu)) {
			foreach ($item->SubMenu->ItemData as $subitem) {
				if ($item->SubMenu->RenderItem($subitem))
					return TRUE;
			}
		}
		return ($item->Allowed && $item->Url <> "");
	}

	// Check if this menu should be rendered
	function RenderMenu() {
		foreach ($this->ItemData as $item) {
			if ($this->RenderItem($item))
				return TRUE;
		}
		return FALSE;
	}

	// Render the menu
	function Render() {
		if (!$this->RenderMenu())
			return;
		echo "<ul";
		if ($this->Id <> "") {
			if (is_numeric($this->Id)) {
				echo " id=\"menu_" . $this->Id . "\"";
			} else {
				echo " id=\"" . $this->Id . "\"";
			}
		}
		if ($this->IsRoot)
			echo " class=\"" . EWRPT_MENUBAR_CLASSNAME . "\"";
		echo ">\n";
		foreach ($this->ItemData as $item) {
			if ($this->RenderItem($item)) {
				echo "<li><a";
				if (!is_null($item->SubMenu) && $item->SubMenu->RenderMenu())
					echo " class=\"" . EWRPT_MENUBAR_SUBMENU_CLASSNAME . "\"";
				if ($item->Url <> "")
					echo " href=\"" . htmlspecialchars(strval($item->Url)) . "\"";
				if ($item->Target <> "")
					echo " target=\"" . $item->Target . "\"";
				echo ">" . $item->Text . "</a>\n";
				if (!is_null($item->SubMenu))
					$item->SubMenu->Render();
				echo "</li>\n";
			}
		}
		echo "</ul>\n";
	}
}

// Menu item class
class crMenuItem {
	var $Id;
	var $Text;
	var $Url;
	var $ParentId; 
	var $SubMenu = NULL; // Data type = crMenu
	var $Source;
	var $Allowed = TRUE;
	var $Target;

	function crMenuItem($id, $text, $url, $parentid, $src, $target, $allowed) {
		$this->Id = $id;
		$this->Text = $text;
		$this->Url = $url;
		$this->ParentId = $parentid;
		$this->Source = $src;
		$this->Target = $target;
		$this->Allowed = $allowed;
	}

	function AddItem($item) { // Add submenu item
		if (is_null($this->SubMenu))
			$this->SubMenu = new crMenu($this->Id);
		$this->SubMenu->AddItem($item);
	}
}

// Report MenuItem Adding event
function MenuItem_Adding(&$Item) {

	//var_dump($Item);
	// Return FALSE if menu item not allowed

	return TRUE;
}
?>
<!-- Begin Main Menu -->
<div class="phpreportmaker">
<?php

// Generate all menu items
$RootMenu = new crMenu("RootMenu");
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(2, $ReportLanguage->Phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("2", "MenuText") . $ReportLanguage->Phrase("SimpleReportMenuItemSuffix"), "giveback_invoicesrpt.php", -1, "", "", TRUE);
$RootMenu->AddMenuItem(133, $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("133", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "onebarsmry.php", -1, "", "", TRUE);
$RootMenu->AddMenuItem(134, $ReportLanguage->Phrase("ChartReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("134", "MenuText") . $ReportLanguage->Phrase("ChartReportMenuItemSuffix"), "onebarsmry.php#cht_Chart1", 133, "", "", TRUE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
