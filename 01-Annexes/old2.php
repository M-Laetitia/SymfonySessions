{# old form yo add module/students #}

//^ Form to add Student

        $programme->setSession($session);
        
        $formStudentSession = $this->createForm(StudentSessionType::class);
        $formStudentSession->handleRequest($request);
 
        if ($formStudentSession->isSubmitted() && $formStudentSession->isValid()) {
            $students = $formStudentSession->getData();

            // Add students to the session
            // get the data from the form. The form handles students, $students should now contain the selected students.
            foreach ($students as $student) {
                $session->addStudent($student);
            }

            $entityManager->persist($session);
            $entityManager->flush();
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }