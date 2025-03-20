jQuery(function (_0x22bcx1) {
    ("use strict");
    _0x22bcx1("form#wrapped").attr("action", "../includes/backend/member-registration.php");
    _0x22bcx1("#wizard_container").wizard({stepsWrapper: "#wrapped", submit: ".submit", beforeSelect: function (_0x22bcx4, _0x22bcx5) {
      if (_0x22bcx1("input#website").val().length != 0) {
        return false;
      }
      ;
      if (!_0x22bcx5.isMovingForward) {
        return true;
      }
      ;
      var _0x22bcx6 = _0x22bcx1(this).wizard("state").step.find(":input");
      return !_0x22bcx6.length || !!_0x22bcx6.valid();
    }}).validate({errorPlacement: function (_0x22bcx2, _0x22bcx3) {
      if (_0x22bcx3.is(":radio") || _0x22bcx3.is(":checkbox")) {
        _0x22bcx2.insertBefore(_0x22bcx3.next());
      } else {
        _0x22bcx2.insertAfter(_0x22bcx3);
      }
    }});
    _0x22bcx1("#progressbar").progressbar();
    _0x22bcx1("#wizard_container").wizard({afterSelect: function (_0x22bcx4, _0x22bcx5) {
      _0x22bcx1("#progressbar").progressbar("value", _0x22bcx5.percentComplete);
      _0x22bcx1("#location").text("(" + _0x22bcx5.stepsComplete + "/" + _0x22bcx5.stepsPossible + ")");
    }});
    _0x22bcx1("#wrapped").validate({ignore: [], rules: {select: {required: true}, password1: {required: true, minlength: 5}, password2: {required: true, minlength: 5, equalTo: "#password1"}}, errorPlacement: function (_0x22bcx2, _0x22bcx3) {
      if (_0x22bcx3.is("select:hidden")) {
        _0x22bcx2.insertAfter(_0x22bcx3.next(".nice-select"));
      } else {
        _0x22bcx2.insertAfter(_0x22bcx3);
      }
    }});
  });
  
  