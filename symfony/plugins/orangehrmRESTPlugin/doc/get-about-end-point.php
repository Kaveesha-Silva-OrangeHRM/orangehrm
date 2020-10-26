/**
* @api {get} /user 13.Get About
* @apiName getAbout
* @apiGroup User
* @apiVersion 0.0.1
* @apiUse AdminDescription
*
*
* @apiSuccess  {String} OrganizationName  Organization Name.
* @apiSuccess  {String} OrangeHRMVersion  OrangeHRM Version.
* @apiSuccess  {String} OrangeHRMVersion  OrangeHRM Version.
* @apiSuccess  {String} DateFormat        Date Format.
* @apiSuccess  {String} Language          Language.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*        {
*            "data":
*                {
*                    "OrganizationName": "OrangeHRM",
*                    "OrganizationCountry": "LK",
*                    "OrangeHRMVersion": "4.5",
*                    "DateFormat": "yyyy-mm-dd ( 2020-10-23 )",
*                    "Language": "English"
*                },
*            "rels":
*                [
*
*                ]
*        }
*
*
*
*/
