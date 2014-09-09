<script src="js/vendor/jquery.js"></script>
<script src="js/foundation/foundation.js"></script>
<script src="js/foundation/foundation.topbar.js"></script>
<script src="js/foundation/foundation.abide.js"></script>
<script src="js/foundation/foundation.equalizer.js"></script>
<script src="js/foundation/foundation.alert.js"></script>
<script src="js/foundation/foundation.accordion.js"></script>
<script src="js/foundation/foundation.tooltip.js"></script>
<script src="js/foundation/foundation.tab.js"></script>
<script src="js/foundation/foundation.dropdown.js"></script>
<script>
    $(document).foundation({
        abide: {
            patterns: {
                valid_password: /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
            }
        }
    });
</script>