$builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                

                
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;


        https://github.com/baku67/ELAN_Symfony_Sessions/blob/master/templates/base.html.twig


        <h3>Programmes</h3>
            <ul class="programmes" data-prototype="{{ form_widget(formAddSession.programmes.vars.prototype)|e('html_attr') }}">
                {% for programmeForm in formAddSession.programmes %}
                    <li>
                        {{ form_row(programmeForm.moduleFormation.name) }}
                        {{ form_row(programmeForm.numberOfDays) }}
                        <button type="button" class="btn btn-danger" onclick="removeProgramme(this)">Remove</button>
                    </li>
                {% endfor %}
                <li><button type="button" class="btn btn-success" onclick="addProgramme()">Add Programme</button></li>
            </ul>
        
            {{ form_row(formAddSession.Validate) }}
        
        {{ form_end(formAddSession) }}
        
        <script>
            function addProgramme() {
                var prototype = document.querySelector('.programmes').getAttribute('data-prototype');
                var index = document.querySelectorAll('.programmes li').length;
                var newForm = prototype.replace(/__name__/g, index);
                document.querySelector('.programmes').insertAdjacentHTML('beforeend', newForm);
            }
        
            function removeProgramme(button) {
                button.parentElement.remove();
            }
        </script>


#[Route('/session/{id}/delete/{studentId}', name: 'remove_student_from_session')]
    public function removeStudent($id, $studentId, EntityManagerInterface $entityManager): Response
    {
       // Perform a raw SQL DELETE query to remove the student from the session
    $sql = 'DELETE FROM Student_Session WHERE session_id = :sessionId AND student_id = :studentId';
    $params = ['sessionId' => $id, 'studentId' => $studentId];

    try {
        $statement = $entityManager->getConnection()->prepare($sql);
        $statement->execute($params);
    } catch (\Exception $e) {
        // Handle exceptions if needed
    }

    // Redirect back to the session page
    return $this->redirectToRoute('show_session', ['id' => $id]);
    }