<?php


namespace Euro\Dao;


use Euro\DBConnector;
use Euro\Model\IncorrectObjectTypeException;
use Euro\Model\NationalFramework;
use Euro\Model\NotFoundItemException;
use Euro\Utils\Utils;
use mysqli_result;

class NationalFrameworkDao extends AbstractDao implements Dao, ModelConverter
{
    private $connection;

    public function __construct()
    {
        $this -> connection = new DBConnector();
    }

    /**
     * @param int $id
     * @return object
     * @throws NotFoundItemException
     */
    public function get(int $id): object
    {
        $item = $this -> connection -> execute_query("SELECT * FROM National_framework WHERE Qualification_ID=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found item. Error: " . DBConnector::$mysqli -> error);
        }

        return $this -> convertMysqlResultToModel($item);
    }

    /**
     * @param object $object
     * @return int
     * @throws IncorrectObjectTypeException
     */
    public function save(object $object): int
    {
        if ($object instanceof NationalFramework) {
            $formatString = sprintf("INSERT INTO National_framework(Qualification_ID, Level_qualification_UA, 
                               Level_qualification_EN, Official_duration_programme_UA, Official_duration_programme_EN, 
                               Access_requirements_UA, Access_requirements_EN, Access_further_study_UA, Access_further_study_EN, 
                               Professional_status_UA, Professional_status_EN)
                VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
                $object -> getQualificationId(),
                $object -> getLevelQualificationUA(),
                $object -> getLevelQualificationEN(),
                $object -> getOfficialDurationProgrammerUA(),
                $object -> getOfficialDurationProgrammerEN(),
                $object -> getAccessRequirementsUA(),
                $object -> getAccessRequirementsEN(),
                $object -> getAccessFurtherStudyUA(),
                $object -> getAccessFurtherStudyEN(),
                $object -> getProfessionalStatusUA(),
                $object -> getProfessionalStatusEN()
            );
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not Graduates");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    public function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM National_framework WHERE Qualification_ID=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found item. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    public function update(object $object): bool
    {
        if ($object instanceof NationalFramework) {
            $formatString = sprintf("UPDATE National_framework SET
                     Level_qualification_UA = '%s',
                     Level_qualification_EN = '%s',
                     Official_duration_programme_UA = '%s',
                     Official_duration_programme_EN = '%s',
                     Access_requirements_UA = '%s',
                     Access_requirements_EN = '%s',
                     Access_further_study_UA = '%s',
                     Access_further_study_EN = '%s',
                     Professional_status_UA = '%s',
                     Professional_status_EN = '%s' WHERE Qualification_ID=%d",
                $object -> getLevelQualificationUA(), $object -> getLevelQualificationEN(), $object -> getOfficialDurationProgrammerUA(), $object -> getOfficialDurationProgrammerEN(),
                $object -> getAccessRequirementsUA(), $object -> getAccessRequirementsEN(), $object -> getAccessFurtherStudyUA(), $object -> getAccessFurtherStudyEN(),
                $object -> getProfessionalStatusUA(), $object -> getProfessionalStatusEN(), $object -> getQualificationId());
            return $this -> connection -> execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not NationalFramework");
    }

    public function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM National_framework");
        return $result -> fetch_all();
    }

    public function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM National_framework WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new NationalFramework($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5],
            $fetchedRow[6],
            $fetchedRow[7],
            $fetchedRow[8],
            $fetchedRow[9],
            $fetchedRow[10]);
    }
}