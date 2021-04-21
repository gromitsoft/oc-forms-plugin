**GromIT.Forms** is a Simple and powerful frontend forms constructor for October CMS.

## Creating forms
For create new form go to **Forms** in the main menu. Then click on **Add form** button.

On next page you can create new empty form. Form key must be unique.

After creating new empty form you can add fields on the form.
For doing this click on **Add field** button and choose field type.
You can add as many fields as you want.

## reCAPTCHA

**GromIT.Forms** has Google reCAPTCHA support.

For use reCAPTCHA on your forms you must go to
**Settings -> Forms plugin settings -> reCAPTCHA** and enter your site key and secret key.
You can obtain keys in [reCAPTCHA control panel](https://www.google.com/recaptcha/admin).

## Rendering Forms

For render form on page go to **CMS -> Pages -> Your page**
and drop **Form** component from the sidebar to the page.
Then in component settings choose form for render.
All forms renders without styling so all CSS is up to you.

Another option is render form by yourself. You can do this like on the sample below.

    title = "Form"
    url = "/"

    [gromitForms]
    ==
    {% set form = gromitForms.getForm('test-form-key') %}
    <div id="my-form">
        <form data-request="gromitForms::onSubmit"
              data-request-update="success: '#my-form'"
              data-request-validate
              data-request-files
              data-request-flash>
            <input type="hidden" name="form_key" value="{{ form.key }}">
            <div>
                <label>
                    Your name
                    <input type="text" name="your_name" required>
                </label>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script src="{{ ['@jquery', '@framework', '@framework.extras'] | theme }}"></script>

Do not forget to add October AJAX framework with extras and field **form_key** with form key.

## Export and import forms

After creating the form you can export this for importing later.
This is very useful for testing form on your development machine and import on production later.

For export for go to form editing page and click on **Export** button at the bottom of the page.

For import form go to **Forms** page and click upload button. Then choose file and click Upload.
If form with key from uploaded file already exists key will be suffixed with random string.

## Export submissions

For export submissions
- go to **Forms -> Submissions**
- click on **Export** button in the toolbar
- choose form
- click **Export**

## Events

There are some events provided by the plugin. You can catch them and do something with this.

    Event::listen(
        'gromit.forms::form.submitting',
        function (Form $form, array $data, ?array $requestData, ?array $userData) {
            //
        }
    );

    Event::listen(
        'gromit.forms::form.submitted',
        function (Form $form, Submission $submission) {
            //
        }
    );
