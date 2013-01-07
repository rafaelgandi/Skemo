Attribute VB_Name = "Module1"
' OOP LIKE DECLARATION FOR ICON
Public Type SKEMOSYSICON
  uSize As Long
  uHandle As Long
  uID As Long                ' id used for the icon. In VB it's not used, so set this Null.
  uFlags As Long             ' tells the sytem how the icon is going to act
  uCallbackMessage As Long   ' the event the icon will respond to
  uIcon As Long              ' the icon that will be placed in the tray
  uTip As String * 64
End Type

Public Const NIM_ADD = 0     ' ADD NEW ICON TO SYSTRAY
Public Const NIM_DELETE = 2  ' REMOVE ICON FROM SYSTRAY
Public Const NIF_MESSAGE = 1 ' tells the system that there is a message handler associated with the icon
Public Const NIF_ICON = 2    ' used to show that there is an icon in the variable being passed to the function
Public Const NIF_TIP = 4     ' ADD A TOOLTIP
Public Const WM_LBUTTONDBLCLK = &H203
Public Const WM_MOUSEMOVE = &H200
Public Const WM_RBUTTONDBLCLK = &H206
Public Const WM_RBUTTONDOWN = &H204

Public Declare Function Shell_NotifyIcon Lib "SHELL32" _
    (ByVal sysACTION As Long, iconData As SKEMOSYSICON) As Integer
  ' iconData is a pointer to SKEMOSYSICON structure
 
Public iconData As SKEMOSYSICON

' -----------------------------

' FOR SKEMO UTILITY

Public conn_SQL As New ADODB.Connection
Public rs_SQL As ADODB.Recordset
Public conn_Excel As New ADODB.Connection
Public rs_Excel As ADODB.Recordset

Public thisColumn() As Integer
Public tableCtrIndicator As Integer
Public sysTimerCtr As Integer
Public sysTimeDelayCtr As Integer
Public secs
Public mins
Public bug As Integer



' ----------------------------


