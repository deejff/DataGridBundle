##This wrapper for KnpPaginator helps you create complete data grid with filters.

######This bundle uses symfony form component to create filters.

######Example controller action:

```php
public function indexAction(Request $request)
{
    $em = $this->get('doctrine.orm.entity_manager');
    $qb = $em->createQueryBuilder()
        ->from('ExampleBundle:User', 'u')
        ->select("u");

    $dataGridBuilder = $this->get('deejff_data_grid.service.data_grid_builder');

    $dataGrid = $dataGridBuilder->build(
        $request,
        $qb,
        $this->createForm(DataGrid\User\FilterType::class),
        new DataGrid\User\FilterQueryBuilder()
    );

    return [
        'dataGrid' => $dataGrid
    ];
}
```

######As you can see to create data grid with filter you have to pass also FilterType and FilterQueryBuilder. Filter type is a instance of symfony form eg.:

```php
class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'label' => 'Name'
                    ]
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'label' => 'Last name'
                    ]
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'label' => 'Email'
                    ]
                ]
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'filter';
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false
            ]
        );
    }
}
```

######The aim of FilterQueryBuilder is a modify query after submit filters. Your own query builder have to implement FilterQueryBuilderInterface eg:

```php
class FilterQueryBuilder implements FilterQueryBuilderInterface
{
    public function build(QueryBuilder $qb, $data)
    {
        if (!empty($data['firstName'])) {
            $qb->andWhere('u.firstName LIKE :firstName');
            $qb->setParameter(':firstName', '%' . $data['firstName'] . '%');
        }

        if (!empty($data['lastName'])) {
            $qb->andWhere('u.lastName LIKE :lastName');
            $qb->setParameter(':lastName', '%' . $data['lastName'] . '%');
        }

        if (!empty($data['email'])) {
            $qb->andWhere('u.email LIKE :email');
            $qb->setParameter(':email', '%' . $data['email'] . '%');
        }
    }
}
```

######To create data grid in view you can use standard embed view DeejffDataGridBundle::data-grid.html.twig or use your own. Sorting links are created by knp_pagination_sortable helper

```php
{% embed "DeejffDataGridBundle::data-grid.html.twig" %}
    {% block data_grid_header %}
        <tr>
            <th>{{ knp_pagination_sortable(dataGrid.pagination, 'Id'|trans, 'u.id') }}</th>
            <th>{{ knp_pagination_sortable(dataGrid.pagination, 'First name'|trans, 'u.firstName') }}</th>
            <th>{{ knp_pagination_sortable(dataGrid.pagination, 'Last name'|trans, 'u.lastName') }}</th>
            <th>{{ knp_pagination_sortable(dataGrid.pagination, 'Email'|trans, 'u.email') }}</th>
        </tr>
    {% endblock %}
    {% block data_grid_content %}
        {% for entity in dataGrid.data %}
            <tr>
                <td>{{ entity.id }}</td>
                <td>{{ entity.firstName }}</td>
                <td>{{ entity.lastName }}</td>
                <td>{{ entity.email }}</td>
            </tr>
        {% endfor %}
    {% endblock %}
{% endembed %}
```

######Please check example project <https://github.com/deejff/Sf3ExampleProject>



